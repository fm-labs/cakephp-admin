<?php
declare(strict_types=1);

namespace Admin;

use Admin\Http\ActionDispatcherListener;
use Admin\Routing\Middleware\AdminMiddleware;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Cache\Cache;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManager;
use Cake\Http\BaseApplication;
use Cake\Http\MiddlewareQueue;
use Cake\Log\Log;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Cupcake\Application;
use Exception;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Admin Plugin
 */
class AdminPlugin extends BasePlugin implements
    //EventListenerInterface,
    AuthenticationServiceProviderInterface
    //\Authorization\AuthorizationServiceProviderInterface
{
    use EventDispatcherTrait;

    public const AUTH_IDENTITY_ATTRIBUTE = 'adminIdentity';

    /**
     * @var \Cake\Http\BaseApplication|\Cupcake\Application
     */
    protected BaseApplication|Application $_app;

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        $app->addPlugin('Settings');
        $app->addPlugin('User');
        $app->addPlugin('Bootstrap');
        $app->addPlugin('Sugar');
        $this->_app = $app;

        /**
         * Configuration
         */
        Configure::load('Admin.admin');
        Configure::load('Admin', 'settings');

        /**
         * Logs
         */
        if (!Log::getConfig('admin')) {
            Log::setConfig('admin', [
                'className' => 'Cake\Log\Engine\FileLog',
                'path' => LOGS,
                'file' => 'admin',
                //'levels' => ['info'],
                'scopes' => ['admin'],
            ]);
        }

        /**
         * Cache config
         */
        if (!Cache::getConfig('admin')) {
            Cache::setConfig('admin', [
                'className' => 'File',
                'duration' => '+1 hours',
                'path' => CACHE,
                'prefix' => 'admin_',
            ]);
        }

        /**
         * DebugKit
         */
        if (Plugin::isLoaded('DebugKit')) {
            $panels = Configure::read('DebugKit.panels', []);
            $panels['Admin.Admin'] = true;
            Configure::write('DebugKit.panels', $panels);
        }

        /**
         * Admin
         */
        Admin::addPlugin(new AdminAdmin());

        EventManager::instance()->on(new ActionDispatcherListener());
    }

    /**
     * @inheritDoc
     */
    public function routes(RouteBuilder $routes): void
    {
        $routes->scope(
            '/' . Admin::$urlPrefix,
            ['prefix' => 'Admin', '_namePrefix' => 'admin:'],
            function (RouteBuilder $routes): void {
                $routes->registerMiddleware('admin_plugins', new AdminMiddleware($this->_app));

                //if (Configure::read('Admin.Auth.authenticationEnabled')) {
                $routes->registerMiddleware('admin_authentication', $this->buildAuthenticationMiddleware());
                //}

                //if (Configure::read('Admin.Auth.authorizationEnabled')) {
                //    $routes->registerMiddleware('admin_authorization', $this->buildAuthorizationMiddleware());
                //    $routes->registerMiddleware('admin_request_authorization', new RequestAuthorizationMiddleware());
                //}

                $routes->applyMiddleware('admin_plugins');

                //if (Configure::read('Admin.Auth.authenticationEnabled')) {
                $routes->applyMiddleware('admin_authentication');
                //}

                //if (Configure::read('Admin.Auth.authorizationEnabled')) {
                //$routes->applyMiddleware('admin_authorization');
                //$routes->applyMiddleware('admin_request_authorization');
                //}

                $fallbackAdminRootUrl = ['plugin' => 'Admin', 'controller' => 'Admin', 'action' => 'index'];
                $adminRootUrl = Configure::read('Admin.Dashboard.url', $fallbackAdminRootUrl);
                $routes->connect('/', $adminRootUrl);

                // load admin routes from admin plugins
                /** @var \Admin\Core\AdminPluginInterface $plugin */
                foreach (Admin::getPlugins() as $plugin) {
                    $pluginName = $plugin->getName();
                    $pluginNamePrefix = sprintf('%s:', Inflector::underscore($pluginName));
                    try {
                        $routes->scope(
                            '/' . $plugin->getRoutingPrefix(),
                            [
                                'plugin' => $plugin->getName() != 'App' ? $plugin->getName() : null,
                                'prefix' => 'Admin',
                                '_namePrefix' => $pluginNamePrefix,
                            ],
                            function (RouteBuilder $routes) use ($plugin): void {
                                $plugin->routes($routes);
                            }
                        );
                    } catch (Exception $ex) {
                        Log::error("Admin routes loading failed: $pluginName: " . $ex->getMessage());
                    }
                }

            //                // [deprecated] register admin plugin routes
            //                // @TODO Remove legacy admin plugin route loader
            //                /** @var \Cake\Core\PluginInterface $plugin */
            //                foreach ($this->_app->getPlugins()->with('routes') as $plugin) {
            //                    $pluginName = $plugin->getName();
            //                    $pluginNamePrefix = sprintf('%s:', Inflector::underscore($pluginName));
            //                    if (method_exists($plugin, 'adminRoutes')) {
            //                        deprecationWarning("Plugin::adminRoutes() is deprecated. Use Admin::routes() instead.");
            //                        try {
            //                            $routes->scope(
            //                                '/' . Inflector::dasherize($pluginName),
            //                                [
            //                                    'plugin' => $pluginName,
            //                                    'prefix' => 'Admin',
            //                                    '_namePrefix' => $pluginNamePrefix,
            //                                ],
            //                                [$plugin, 'adminRoutes']
            //                            );
            //                        } catch (\Exception $ex) {
            //                            Log::error("Admin plugin loading failed: $pluginName: " . $ex->getMessage());
            //                        }
            //                    }
            //                }

            //                // catch-all fallback
            //                $routes->connect(
            //                    '/{path}',
            //                    ['plugin' => 'Admin', 'controller' => 'Admin', 'action' => 'fallback'],
            //                    ['path' => '.*', 'pass' => ['path']]
            //                );

                //admin:index
                $routes->connect(
                    '/',
                    ['plugin' => 'Admin', 'controller' => 'Admin', 'action' => 'index'],
                    ['_name' => 'index']
                );

                //admin:auth:*
                $routes->scope(
                    '/auth',
                    ['prefix' => 'Admin', 'plugin' => 'Admin', '_namePrefix' => 'auth:'],
                    function (RouteBuilder $routes): void {

                        $routes->connect(
                            '/',
                            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'index'],
                            ['_name' => 'index']
                        );

                        // admin:auth:user:login
                        $routes->connect(
                            '/login',
                            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'login'],
                            ['_name' => 'user:login']
                        );

                        // admin:auth:user:checkauth
                        $routes->connect(
                            '/session',
                            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'session'],
                            ['_name' => 'user:checkauth']
                        );

                        // admin:auth:user:loginsuccess
                        $routes->connect(
                            '/login-success',
                            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'loginSuccess'],
                            ['_name' => 'user:loginsuccess']
                        );

                        // admin:auth:user:logout
                        $routes->connect(
                            '/logout',
                            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'logout'],
                            ['_name' => 'user:logout']
                        );

                        // admin:auth:user:profile
                        $routes->connect(
                            '/user',
                            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'user'],
                            ['_name' => 'user:profile']
                        );

                        //$routes->fallbacks(DashedRoute::class);
                    }
                );

                $this->dispatchEvent('Admin.Routes.setup', ['routes' => $routes]);
            } # End of admin root scope
        );
    }

    /**
     * @return \Authentication\Middleware\AuthenticationMiddleware
     */
    public function buildAuthenticationMiddleware(): AuthenticationMiddleware
    {
        return new AuthenticationMiddleware($this);
    }

    /**
     * Returns an authentication service instance.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @return \Authentication\AuthenticationServiceInterface
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService([
            //'authenticators' => [],
            //'identifiers' => [],
            //'identityClass' => Identity::class,
            'identityAttribute' => static::AUTH_IDENTITY_ATTRIBUTE,
            'queryParam' => 'redirect',
            'unauthenticatedRedirect' => Router::url(['_name' => 'admin:auth:user:login']),
        ]);

        $fields = [
            'username' => 'username',
            'password' => 'password',
        ];

        // Load the authenticators, you want session first
        $service->loadAuthenticator('Authentication.Session', [
            'fields' => [
                'username' => 'username',
            ],
            'sessionKey' => 'Admin',
            'identify' => true,
            'identityAttribute' => static::AUTH_IDENTITY_ATTRIBUTE,
        ]);
        $service->loadAuthenticator('Authentication.Form', [
            'loginUrl' => null,
            'urlChecker' => 'Authentication.Default',
            'fields' => $fields,
        ]);

        // Load identifiers
        $service->loadIdentifier('Authentication.Password', [
            'resolver' => [
                'className' => 'Authentication.Orm',
                'userModel' => 'Admin.Users',
                'finder' => 'authUser',
            ],
            'fields' => $fields,
            'passwordHasher' => null,
        ]);

        return $service;
    }

//    /**
//     * @return \Authorization\Middleware\AuthorizationMiddleware
//     */
//    public function buildAuthorizationMiddleware(): AuthorizationMiddleware
//    {
//        return new AuthorizationMiddleware($this, [
//            'requireAuthorizationCheck' => false,
//            //'identityDecorator' => function ($auth, User $user) {
//            //    return $user->setAuthorization($auth);
//            //},
//            'unauthorizedHandler' => [
//                'className' => FlashRedirectHandler::class, //PageRedirectHandler::class, // 'Authorization.Redirect',
//                'url' => '/' . Admin::$urlPrefix . '/system/auth/unauthorized',
//                'queryParam' => 'unauthorizedUrl',
//                'exceptions' => [
//                    \Authorization\Exception\MissingIdentityException::class,
//                    \Authorization\Exception\ForbiddenException::class,
//                ],
//            ],
//        ]);
//    }
//
//    /**
//     * Returns authorization service instance.
//     *
//     * @param \Psr\Http\Message\ServerRequestInterface $request Request
//     * @return \Authorization\AuthorizationServiceInterface
//     */
//    public function getAuthorizationService(ServerRequestInterface $request): AuthorizationServiceInterface
//    {
//        $resolver = new MapResolver();
//        // this is required for the RequestAuthorizationMiddleware to work
//        $resolver->map(ServerRequest::class, AdminRequestPolicy::class);
//
//        return new AuthorizationService($resolver);
//    }

    /**
     * @inheritDoc
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        return $middlewareQueue;
    }
}
