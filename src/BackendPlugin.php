<?php

namespace Backend;

use Backend\Http\ActionDispatcherListener;
use Backend\View\BackendView;
use Banana\Application;
use Banana\Menu\Menu;
use Banana\Plugin\BasePlugin;
use Banana\Plugin\PluginInterface;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Settings\SettingsManager;

class BackendPlugin extends BasePlugin implements EventListenerInterface
{
    use EventDispatcherTrait;

    protected $_name = "Backend";

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
    public function __construct($config)
    {
        parent::__construct($config);
        $this->_backend = new Backend();
    }

    /**
     * {@inheritDoc}
     */
    public function implementedEvents()
    {
        return [
            'Backend.Theme.Sidebar.buildMenu' => 'backendThemeSidebarBuildMenu',
            'Backend.Theme.Navbar.buildMenu' => 'backendThemeNavbarBuildMenu',
            'Backend.Menu.init' => ['callable' => 'backendMenuInit' ],
            'Backend.Menu.build.admin_primary' => ['callable' => 'buildBackendMenu', 'priority' => 99 ],
            'Backend.Menu.build.admin_system' => ['callable' => 'buildBackendSystemMenu', 'priority' => 99 ],
            'Settings.build' => 'settings'
        ];
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
                'group' => 'backend_form',
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
    public function buildBackendMenu(Event $event, \Banana\Menu\Menu $menu)
    {

        if (Configure::read('debug')) {
            $menu->addItem([
                'title' => __d('backend', 'Developer'),
                'data-icon' => 'paint-brush',
                'url' => ['plugin' => 'Backend', 'controller' => 'Design', 'action' => 'index'],
                'children' => [
                    'design' => [
                        'title' => __d('backend', 'Design'),
                        'data-icon' => 'paint-brush',
                        'url' => ['plugin' => 'Backend', 'controller' => 'Design', 'action' => 'index'],
                    ],
                    /*
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
                    */
                ]
            ]);
        }
    }

    /**
     * @param Event $event The event object
     * @return void
     */
    public function buildBackendSystemMenu(Event $event, \Banana\Menu\Menu $menu)
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
                        '_namePrefix' => sprintf("admin:%s:", Inflector::underscore($pluginName))
                    ], [$instance, 'backendRoutes']);
                } catch (\Exception $ex) {
                    Log::error("Backend plugin loading failed: $pluginName: " . $ex->getMessage());
                }
            } else {

//                try {
//                    Router::scope($urlPrefix . Inflector::underscore($pluginName), [
//                        'plugin' => $pluginName,
//                        'prefix' => 'admin',
//                        '_namePrefix' => sprintf("admin:%s:", Inflector::underscore($pluginName))
//                    ], function(RouteBuilder $routes) {
//                        $routes->fallbacks('DashedRoute');
//                    });
//                } catch (\Exception $ex) {
//                    Log::error("Backend plugin loading failed: $pluginName: " . $ex->getMessage());
//                }
            }
        }

        $event = $this->dispatchEvent('Backend.Routes.setup', ['routes' => $routes]);
        return $event->data['routes'];

        return $routes;
    }

    /**
     * {@inheritDoc}
     */
    public function bootstrap(Application $app)
    {
        parent::bootstrap($app);

        $app->addPlugin("User");
        $app->addPlugin("Bootstrap");

        EventManager::instance()->on($this);
        EventManager::instance()->on(new ActionDispatcherListener());

        $this->_app = $app;
//        foreach ($this->_app->plugins()->loaded() as $pluginName) {
//            $instance = $this->_app->plugins()->get($pluginName);
//            if (method_exists($instance, 'backendBootstrap')) {
//                try {
//                    call_user_func([$instance, 'backendBootstrap'], $this->_backend);
//                } catch (\Exception $ex) {
//                    Log::error("Backend plugin bootstrapping failed: $pluginName: " . $ex->getMessage());
//                }
//            }
//        }

        Backend::addFilter('backend_menu_build', function ($menu) {
            return $menu;
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

        // admin:backend:auth:logout
        $routes->connect(
            '/logout',
            ['controller' => 'Auth', 'action' => 'logout'],
            [ '_name' => 'user:logout']
        );

        // admin:backend:auth:user
        $routes->connect(
            '/user',
            ['controller' => 'Auth', 'action' => 'user'],
            [ '_name' => 'user:profile']
        );

        // admin:backend:dashboard
        $routes->connect(
            '/',
            ['controller' => 'Backend', 'action' => 'index'],
            ['_name' => 'dashboard']
        );

        $routes->fallbacks('DashedRoute');

        return $routes;
    }
}
