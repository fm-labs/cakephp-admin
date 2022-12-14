<?php
declare(strict_types=1);

namespace Admin;

use Admin\Authorization\Middleware\UnauthorizedHandler\FlashRedirectHandler;
use Admin\Http\ActionDispatcherListener;
use Admin\Policy\AdminRequestPolicy;
use Admin\Routing\Middleware\AdminMiddleware;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\Identifier\IdentifierInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Middleware\RequestAuthorizationMiddleware;
use Authorization\Policy\MapResolver;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManager;
use Cake\Http\MiddlewareQueue;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Cupcake\Plugin\BasePlugin;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Admin Plugin
 */
class Plugin extends BasePlugin implements
    //EventListenerInterface,
    \Authentication\AuthenticationServiceProviderInterface
    //\Authorization\AuthorizationServiceProviderInterface
{
    use EventDispatcherTrait;

    public const AUTH_IDENTITY_ATTRIBUTE = 'adminIdentity';

    /**
     * @var \Cake\Http\BaseApplication|\Cupcake\Application
     */
    protected $_app;

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
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
        if (\Cake\Core\Plugin::isLoaded('DebugKit')) {
            $panels = Configure::read('DebugKit.panels', []);
            $panels['Admin.Admin'] = true;
            Configure::write('DebugKit.panels', $panels);
        }

        $app->addPlugin('User');
        $app->addPlugin('Bootstrap');
        $app->addPlugin('Sugar');
        $this->_app = $app;

        class_alias('\\Sugar\\View\\Helper\\BoxHelper', '\\Admin\\View\\Helper\\BoxHelper');
        class_alias('\\Sugar\\View\\Helper\\CodeEditorHelper', '\\Admin\\View\\Helper\\CodeEditorHelper');
        class_alias('\\Sugar\\View\\Helper\\DataTableHelper', '\\Admin\\View\\Helper\\DataTableHelper');
        class_alias('\\Sugar\\View\\Helper\\DataTableJsHelper', '\\Admin\\View\\Helper\\DataTableJsHelper');
        class_alias('\\Sugar\\View\\Helper\\FlagIconHelper', '\\Admin\\View\\Helper\\FlagIconHelper');
        class_alias('\\Sugar\\View\\Helper\\FormatterHelper', '\\Admin\\View\\Helper\\FormatterHelper');
        class_alias('\\Sugar\\View\\Helper\\JsTreeHelper', '\\Admin\\View\\Helper\\JsTreeHelper');
        class_alias('\\Sugar\\View\\Helper\\Select2Helper', '\\Admin\\View\\Helper\\Select2Helper');
        class_alias('\\Sugar\\View\\Helper\\SumoSelectHelper', '\\Admin\\View\\Helper\\SumoSelectHelper');
        class_alias('\\Sugar\\View\\Helper\\SweetAlert2Helper', '\\Admin\\View\\Helper\\SweetAlert2Helper');
        class_alias('\\Sugar\\View\\Helper\\ToastrHelper', '\\Admin\\View\\Helper\\ToastrHelper');

        class_alias('\\Sugar\\View\\Widget\\CheckboxWidget', '\\Admin\\View\\Widget\\CheckboxWidget');
        class_alias('\\Sugar\\View\\Widget\\CodeEditorWidget', '\\Admin\\View\\Widget\\CodeEditorWidget');
        class_alias('\\Sugar\\View\\Widget\\DatePickerWidget', '\\Admin\\View\\Widget\\DatePickerWidget');
        class_alias('\\Sugar\\View\\Widget\\DateRangePickerWidget', '\\Admin\\View\\Widget\\DateRangePickerWidget');
        class_alias('\\Sugar\\View\\Widget\\HtmlEditorWidget', '\\Admin\\View\\Widget\\HtmlEditorWidget');
        class_alias('\\Sugar\\View\\Widget\\ImageSelectWidget', '\\Admin\\View\\Widget\\ImageSelectWidget');
        class_alias('\\Sugar\\View\\Widget\\Select2Widget', '\\Admin\\View\\Widget\\Select2Widget');
        class_alias('\\Sugar\\View\\Widget\\SumoSelectBoxWidget', '\\Admin\\View\\Widget\\SumoSelectBoxWidget');
        class_alias('\\Sugar\\View\\Widget\\TimePickerWidget', '\\Admin\\View\\Widget\\TimePickerWidget');

        EventManager::instance()->on(new ActionDispatcherListener());
        Admin::addPlugin(new AdminPlugin());
    }

    /**
     * @inheritDoc
     */
    public function routes(\Cake\Routing\RouteBuilder $routes): void
    {
        $routes->scope(
            '/' . Admin::$urlPrefix,
            ['prefix' => 'Admin', '_namePrefix' => 'admin:'],
            function (RouteBuilder $routes) {
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

                //admin:dashboard
                $routes->connect(
                    '/',
                    ['plugin' => 'Admin', 'controller' => 'Admin', 'action' => 'index'],
                    ['_name' => 'dashboard']
                );

                //admin:system:*
                $routes->scope(
                    '/system',
                    ['prefix' => 'Admin', 'plugin' => 'Admin', '_namePrefix' => 'system:'],
                    function (RouteBuilder $routes) {
                        $routes->connect(
                            '/login',
                            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'login'],
                            ['_name' => 'user:login']
                        );
                        $routes->connect(
                            '/session',
                            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'session'],
                            ['_name' => 'user:checkauth']
                        );
                        $routes->connect(
                            '/login-success',
                            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'loginSuccess'],
                            ['_name' => 'user:loginsuccess']
                        );

                        // admin:system:auth:logout
                        $routes->connect(
                            '/logout',
                            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'logout'],
                            [ '_name' => 'user:logout']
                        );

                        // admin:system:auth:user
                        $routes->connect(
                            '/user',
                            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'user'],
                            [ '_name' => 'user:profile']
                        );

                        // admin:system:dashboard
                        $routes->connect(
                            '/',
                            ['plugin' => 'Admin', 'controller' => 'Admin', 'action' => 'index'],
                            ['_name' => 'dashboard']
                        );

                        $routes->fallbacks(DashedRoute::class);
                    }
                );

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
                            [$plugin, 'routes']
                        );
                    } catch (\Exception $ex) {
                        Log::error("Admin routes loading failed: $pluginName: " . $ex->getMessage());
                    }
                }

                // [deprecated] register admin plugin routes
                // @TODO Remove legacy admin plugin route loader
                /** @var \Cake\Core\PluginInterface $plugin */
                foreach ($this->_app->getPlugins()->with('routes') as $plugin) {
                    $pluginName = $plugin->getName();
                    $pluginNamePrefix = sprintf('%s:', Inflector::underscore($pluginName));
                    if (method_exists($plugin, 'adminRoutes')) {
                        try {
                            $routes->scope(
                                '/' . Inflector::dasherize($pluginName),
                                [
                                    'plugin' => $pluginName,
                                    'prefix' => 'Admin',
                                    '_namePrefix' => $pluginNamePrefix,
                                ],
                                [$plugin, 'adminRoutes']
                            );
                        } catch (\Exception $ex) {
                            Log::error("Admin plugin loading failed: $pluginName: " . $ex->getMessage());
                        }
                    }
                }

                // catch-all fallback
                $routes->connect(
                    '/{path}',
                    ['plugin' => 'Admin', 'controller' => 'Admin', 'action' => 'fallback'],
                    ['path' => '.*', 'pass' => ['path']]
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
            'unauthenticatedRedirect' => Router::url(['_name' => 'admin:system:user:login']),
        ]);

        $fields = [
            IdentifierInterface::CREDENTIAL_USERNAME => 'username',
            IdentifierInterface::CREDENTIAL_PASSWORD => 'password',
        ];

        // Load the authenticators, you want session first
        $service->loadAuthenticator('Authentication.Session', [
            'fields' => [
                IdentifierInterface::CREDENTIAL_USERNAME => 'username',
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
