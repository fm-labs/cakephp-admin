<?php
declare(strict_types=1);

namespace Admin;

use Admin\Http\ActionDispatcherListener;
use Admin\Routing\Middleware\AdminMiddleware;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\Event;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Http\MiddlewareQueue;
use Cake\Log\Log;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Cake\Utility\Inflector;
use Cupcake\Plugin\BasePlugin;
use Settings\SettingsManager;

/**
 * Admin Plugin
 */
class Plugin extends BasePlugin implements EventListenerInterface
{
    use EventDispatcherTrait;

    /**
     * @var \Cake\Http\BaseApplication|\Cupcake\Application
     */
    protected $_app;

    /**
     * {@inheritDoc}
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
                'scopes' => ['admin', 'admin'],
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

        $app->addPlugin("User");
        $app->addPlugin("Bootstrap");

        EventManager::instance()->on($this);
        EventManager::instance()->on(new ActionDispatcherListener());

        $this->_app = $app;
    }

    /**
     * {@inheritDoc}
     */
    public function routes(\Cake\Routing\RouteBuilder $routes): void
    {
        parent::routes($routes);

        $routes->scope(
            '/' . Admin::$urlPrefix,
            ['prefix' => 'Admin', '_namePrefix' => 'admin:'],
            function (RouteBuilder $routes) {
                $routes->registerMiddleware('admin', new AdminMiddleware($this->_app));
                $routes->applyMiddleware('admin');
                $routes->connect('/', ['plugin' => 'Admin', 'controller' => 'Admin', 'action' => 'index']);

                $routes->scope(
                    '/system',
                    ['prefix' => 'Admin', 'plugin' => 'Admin', '_namePrefix' => 'admin:'],
                    function ($routes) {
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

                        // admin:admin:auth:logout
                        $routes->connect(
                            '/logout',
                            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'logout'],
                            [ '_name' => 'user:logout']
                        );

                        // admin:admin:auth:user
                        $routes->connect(
                            '/user',
                            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'user'],
                            [ '_name' => 'user:profile']
                        );

                        // admin:admin:dashboard
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
                    try {
                        $routes->scope(
                            '/' . Inflector::dasherize($pluginName),
                            [
                                'plugin' => $plugin->getName() != "App" ? $plugin->getName() : null,
                                'prefix' => 'Admin',
                                '_namePrefix' => sprintf("%s:", Inflector::underscore($pluginName)),
                            ],
                            [$plugin, 'routes']
                        );
                    } catch (\Exception $ex) {
                        Log::error("Admin plugin loading failed: $pluginName: " . $ex->getMessage());
                    }
                }

                // [deprecated] register admin plugin routes
                // @TODO Remove legacy admin plugin route loader
                /** @var \Cake\Core\PluginInterface $plugin */
                foreach ($this->_app->getPlugins()->with('routes') as $plugin) {
                    $pluginName = $plugin->getName();
                    if (method_exists($plugin, 'adminRoutes')) {
                        try {
                            $routes->scope(
                                '/' . Inflector::dasherize($pluginName),
                                [
                                    'plugin' => $plugin->getName(),
                                    'prefix' => 'Admin',
                                    '_namePrefix' => sprintf("%s:", Inflector::underscore($pluginName)),
                                ],
                                [$plugin, 'adminRoutes']
                            );
                        } catch (\Exception $ex) {
                            Log::error("Admin plugin loading failed: $pluginName: " . $ex->getMessage());
                        }
                    }
                }

                $event = $this->dispatchEvent('Admin.Routes.setup', ['routes' => $routes]);
            } # End of admin root scope
        );
    }

    /**
     * {@inheritDoc}
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        return $middlewareQueue;
    }

    public function adminConfigurationUrl()
    {
        return \Cake\Core\Plugin::isLoaded('Settings') ? ['_name' => 'settings:manage', $this->getName()] : null;
    }

    /**
     * {@inheritDoc}
     */
    public function implementedEvents(): array
    {
        return [
            'Admin.Menu.build.admin_primary' => ['callable' => 'buildAdminMenu', 'priority' => 99 ],
            'Admin.Menu.build.admin_system' => ['callable' => 'buildAdminSystemMenu', 'priority' => 99 ],
            'Settings.build' => 'settings',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function settings(Event $event, SettingsManager $settings)
    {
        //$settings::register($this->getName(), Settings::class);

        $settings->load('Admin.settings');
        $settings->add('Admin.Form', [
            'Admin.CodeEditor.Ace.theme' => [
                'default' => 'twilight',
                'input' => [
                    'type' => 'select',
                    'options' => [
                        'chaos' => 'chaos',
                        'chrome' => 'chrome',
                        'clouds' => 'clouds',
                        'cloud_midnight' => 'cloud_midnight',
                        'cobalt' => 'cobalt',
                        'crimson_editor' => 'crimson_editor',
                        'dawn' => 'dawn',
                        'dracula' => 'dracula',
                        'dreamweaver' => 'dreamweaver',
                        'eclipse' => 'eclipse',
                        'github' => 'github',
                        'gob' => 'gob',
                        'gruvbox' => 'gruvbox',
                        'idle_fingers' => 'idle_fingers',
                        'iplastic' => 'iplastic',
                        'katzenmilch' => 'katzenmilch',
                        'kr_theme' => 'kr_theme',
                        'kuroir' => 'kuroir',
                        'merbivore' => 'merbivore',
                        'merbivore_soft' => 'merbivore_soft',
                        'mono_industrial' => 'mono_industrial',
                        'monokai' => 'monokai',
                        'pastel_on_dark' => 'pastel_on_dark',
                        'solarized_dark' => 'solarized_dark',
                        'solarized_light' => 'solarized_light',
                        'sqlserver' => 'sqlserver',
                        'terminal' => 'terminal',
                        'textmate' => 'textmate',
                        'tomorrow' => 'tomorrow',
                        'tomorrow_night' => 'tomorrow_night',
                        'tomorrow_night_blue' => 'tomorrow_night_blue',
                        'tomorrow_night_bright' => 'tomorrow_night_bright',
                        'tomorrow_night_eighties' => 'tomorrow_night_eighties',
                        'twilight' => 'twilight',
                        'vibrant_ink' => 'vibrant_ink',
                        'xcode' => 'xcode',
                    ],
                ],
            ],

        ]);
    }

    /**
     * @param \Cake\Event\Event $event The event object
     * @return void
     */
    public function buildAdminMenu(Event $event, \Cupcake\Menu\Menu $menu)
    {
        if (Configure::read('debug')) {
            $menu->addItem([
                'title' => __d('admin', 'Developer'),
                'data-icon' => 'paint-brush',
                'url' => ['plugin' => 'Admin', 'controller' => 'Design', 'action' => 'index'],
                'children' => [
                    'design' => [
                        'title' => __d('admin', 'Design'),
                        'data-icon' => 'paint-brush',
                        'url' => ['plugin' => 'Admin', 'controller' => 'Design', 'action' => 'index'],
                    ],
                    /*
                    'design_form' => [
                        'title' => __d('admin', 'Forms'),
                        'data-icon' => 'paint-brush',
                        'url' => ['plugin' => 'Admin', 'controller' => 'Design', 'action' => 'index', 'section' => 'form'],
                    ],
                    'design_boxes' => [
                        'title' => __d('admin', 'Boxes'),
                        'data-icon' => 'paint-brush',
                        'url' => ['plugin' => 'Admin', 'controller' => 'Design', 'action' => 'index', 'section' => 'boxes'],
                    ],
                    'design_tables' => [
                        'title' => __d('admin', 'Tables'),
                        'data-icon' => 'paint-brush',
                        'url' => ['plugin' => 'Admin', 'controller' => 'Design', 'action' => 'index', 'section' => 'tables'],
                    ],
                    'design_component' => [
                        'title' => __d('admin', 'Components'),
                        'data-icon' => 'paint-brush',
                        'url' => ['plugin' => 'Admin', 'controller' => 'Design', 'action' => 'index', 'section' => 'component'],
                    ],
                    'design_tabs' => [
                        'title' => __d('admin', 'Tabs'),
                        'data-icon' => 'paint-brush',
                        'url' => ['plugin' => 'Admin', 'controller' => 'Design', 'action' => 'index', 'section' => 'tabs'],
                    ]
                    */
                ],
            ]);
        }
    }

    /**
     * @param \Cake\Event\Event $event The event object
     * @return void
     */
    public function buildAdminSystemMenu(Event $event, \Cupcake\Menu\Menu $menu)
    {
        $items = $this->_getMenuItems();
        foreach ($items as $item) {
            $menu->addItem($item);
        }
    }

    /**
     * @return array
     */
    protected function _getMenuItems()
    {
        return $items = [
//                'overview' => [
//                    'title' => 'Overview',
//                    'url' => ['plugin' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'],
//                    'data-icon' => 'info'
//                ],
            'system' => [
                'title' => 'Systeminfo',
                'url' => ['plugin' => 'Admin', 'controller' => 'System', 'action' => 'index'],
                'data-icon' => 'info',
            ],
            'logs' => [
                'title' => 'Logs',
                'url' => ['plugin' => 'Admin', 'controller' => 'Logs', 'action' => 'index'],
                'data-icon' => 'file-text-o',
            ],
            'cache' => [
                'title' => 'Cache',
                'url' => ['plugin' => 'Admin', 'controller' => 'Cache', 'action' => 'index'],
                'data-icon' => 'hourglass-o',
            ],
            /*
            'users' => [
                'title' => 'Users',
                'url' => ['plugin' => 'Admin', 'controller' => 'Users', 'action' => 'index'],
                'data-icon' => 'users',
            ],
            */
            'plugins' => [
                'title' => 'Plugins',
                'url' => ['plugin' => 'Admin', 'controller' => 'Plugins', 'action' => 'index'],
                'data-icon' => 'puzzle-piece',
            ],
        ];
    }
}
