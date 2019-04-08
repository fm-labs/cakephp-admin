<?php

namespace Backend;

use Backend\Http\ActionDispatcherListener;
use Backend\View\BackendView;
use Banana\Application;
use Banana\Menu\Menu;
use Banana\Plugin\PluginInterface;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Http\MiddlewareQueue;
use Cake\Log\Log;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Settings\SettingsManager;

class BackendPlugin implements PluginInterface, EventListenerInterface
{
    /**
     * @var \Banana\Application
     */
    protected $_app;

    /**
     * @var Backend
     */
    protected $_backend;

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        $this->_backend = new Backend();
    }

    /**
     * {@inheritDoc}
     */
    public function implementedEvents()
    {
        return [
            //'Backend.Sidebar.build' => ['callable' => 'buildBackendSidebarMenu', 'priority' => 99 ],
            'Backend.SysMenu.build' => ['callable' => 'buildBackendSystemMenu', 'priority' => 99 ],
            //'Backend.Menu.build'    => ['callable' => 'buildBackendMenu', 'priority' => 99 ],
           // 'View.beforeLayout'     => ['callable' => 'beforeLayout'],
            'Settings.build' => 'settings'
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function beforeLayout(Event $event)
    {
        if ($event->subject() instanceof BackendView && $event->subject()->plugin == "Backend") {
            //$menu = new Menu($this->_getMenuItems());
            //$event->subject()->set('backend.sidebar.menu', $menu);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function settings(Event $event, SettingsManager $settings)
    {
        $settings->load('Backend.settings');
        $settings->addGroup('backend_form', ['label' => __('Backend Form')]);
        $settings->add('backend_form', [
            'Backend.CodeEditor.Ace.theme' => [
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
                    ]
                ]
            ]

        ]);
    }

    /**
     * @param Event $event The event object
     * @return void
     */
    public function buildBackendSidebarMenu(Event $event)
    {
        if ($event->subject() instanceof \Banana\Menu\Menu) {
            //$settingsMenu = new Menu();
            //$this->eventManager()->dispatch(new Event('Backend.SysMenu.build', $settingsMenu));
            $event->subject()->addItem([
                'title' => __d('backend', 'System'),
                'url' => ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                'data-icon' => 'gears',
                'children' => $this->_getMenuItems(),
            ]);

            if (Configure::read('debug')) {
                $event->subject()->addItem([
                    'title' => __d('backend', 'Design'),
                    'data-icon' => 'paint-brush',
                    'url' => ['plugin' => 'Backend', 'controller' => 'Design', 'action' => 'index'],
                    'children' => [
                        'design_form' => [
                            'title' => __d('backend', 'Forms'),
                            'data-icon' => 'paint-brush',
                            'url' => ['plugin' => 'Backend', 'controller' => 'Design', 'action' => 'index', 'section' => 'form'],
                        ],
                        'design_boxes' => [
                            'title' => __d('backend', 'Boxes'),
                            'data-icon' => 'paint-brush',
                            'url' => ['plugin' => 'Backend', 'controller' => 'Design', 'action' => 'index', 'section' => 'boxes'],
                        ],
                        'design_tables' => [
                            'title' => __d('backend', 'Tables'),
                            'data-icon' => 'paint-brush',
                            'url' => ['plugin' => 'Backend', 'controller' => 'Design', 'action' => 'index', 'section' => 'tables'],
                        ],
                        'design_component' => [
                            'title' => __d('backend', 'Components'),
                            'data-icon' => 'paint-brush',
                            'url' => ['plugin' => 'Backend', 'controller' => 'Design', 'action' => 'index', 'section' => 'component'],
                        ],
                        'design_tabs' => [
                            'title' => __d('backend', 'Tabs'),
                            'data-icon' => 'paint-brush',
                            'url' => ['plugin' => 'Backend', 'controller' => 'Design', 'action' => 'index', 'section' => 'tabs'],
                        ]
                    ]
                ]);
            }
        }
    }

    /**
     * @param Event $event The event object
     * @return void
     */
    public function buildBackendMenu(Event $event)
    {
        if ($event->subject() instanceof \Banana\Menu\Menu) {
        }
    }

    /**
     * @param Event $event The event object
     * @return void
     */
    public function buildBackendSystemMenu(Event $event)
    {
        if ($event->subject() instanceof \Banana\Menu\Menu) {
            $items = $this->_getMenuItems();
            foreach ($items as $item) {
                $event->subject()->addItem($item);
            }
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
//                    'url' => ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'index'],
//                    'data-icon' => 'info'
//                ],
            'system' => [
                'title' => 'Systeminfo',
                'url' => ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                'data-icon' => 'info'
            ],
            'logs' => [
                'title' => 'Logs',
                'url' => ['plugin' => 'Backend', 'controller' => 'Logs', 'action' => 'index'],
                'data-icon' => 'file-text-o',
            ],
            'cache' => [
                'title' => 'Cache',
                'url' => ['plugin' => 'Backend', 'controller' => 'Cache', 'action' => 'index'],
                'data-icon' => 'hourglass-o',
            ],
            /*
            'users' => [
                'title' => 'Users',
                'url' => ['plugin' => 'Backend', 'controller' => 'Users', 'action' => 'index'],
                'data-icon' => 'users',
            ],
            */
            'plugins' => [
                'title' => 'Plugins',
                'url' => ['plugin' => 'Backend', 'controller' => 'Plugins', 'action' => 'index'],
                'data-icon' => 'puzzle-piece',
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function routes(RouteBuilder $routes)
    {
        $urlPrefix = '/' . trim(Backend::$urlPrefix, '/') . '/';
        foreach ($this->_app->plugins()->loaded() as $pluginName) {
            $instance = $this->_app->plugins()->get($pluginName);
            if (method_exists($instance, 'backendRoutes')) {
                try {
                    Router::scope($urlPrefix . Inflector::underscore($pluginName), [
                        'plugin' => $pluginName,
                        'prefix' => 'admin',
                        '_namePrefix' => sprintf("%s:admin:", Inflector::underscore($pluginName))
                    ], [$instance, 'backendRoutes']);
                } catch (\Exception $ex) {
                    Log::error("Backend plugin loading failed: $pluginName: " . $ex->getMessage());
                }
            }
        }

        return $routes;
    }

    /**
     * {@inheritDoc}
     */
    public function bootstrap(Application $app)
    {
        EventManager::instance()->on($this);
        EventManager::instance()->on(new ActionDispatcherListener());

        $this->_app = $app;
        foreach ($this->_app->plugins()->loaded() as $pluginName) {
            $instance = $this->_app->plugins()->get($pluginName);
            if (method_exists($instance, 'backendBootstrap')) {
                try {
                    call_user_func([$instance, 'backendBootstrap'], $this->_backend);
                } catch (\Exception $ex) {
                    Log::error("Backend plugin bootstrapping failed: $pluginName: " . $ex->getMessage());
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function backendBootstrap(Backend $backend)
    {
        $backend->hook('backend.menu.build', function (Menu $menu) {
            $menu->addItem([
                'title' => 'System',
                'url' => ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                'data-icon' => 'gears',
                'children' => $this->_getMenuItems(),
            ]);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function backendRoutes(RouteBuilder $routes)
    {
        $routes->connect(
            '/login',
            ['controller' => 'Auth', 'action' => 'login'],
            ['_name' => 'user:login']
        );
        $routes->connect(
            '/session',
            ['controller' => 'Auth', 'action' => 'session'],
            ['_name' => 'user:checkauth']
        );
        $routes->connect(
            '/login-success',
            ['controller' => 'Auth', 'action' => 'loginSuccess'],
            ['_name' => 'user:loginsuccess']
        );

        // backend:admin:auth:logout
        $routes->connect(
            '/logout',
            ['controller' => 'Auth', 'action' => 'logout'],
            [ '_name' => 'user:logout']
        );

        // backend:admin:auth:user
        $routes->connect(
            '/user',
            ['controller' => 'Auth', 'action' => 'user'],
            [ '_name' => 'user:profile']
        );

        // backend:admin:dashboard
        $routes->connect(
            '/',
            ['controller' => 'Backend', 'action' => 'index'],
            ['_name' => 'dashboard']
        );
        $routes->fallbacks('DashedRoute');

        return $routes;
    }

    /**
     * {@inheritDoc}
     */
    public function middleware(MiddlewareQueue $middleware)
    {
    }
}
