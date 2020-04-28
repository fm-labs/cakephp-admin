<?php
declare(strict_types=1);

namespace Admin;

use Admin\Http\ActionDispatcherListener;
use Banana\Plugin\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\Event;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Settings\SettingsManager;

/**
 * Admin Plugin
 */
class Plugin extends BasePlugin implements EventListenerInterface
{
    use EventDispatcherTrait;

    /**
     * @var \Banana\Application
     */
    protected $_app;

    /**
     * @var \Admin\Admin
     */
    protected $_admin;

    /**
     * {@inheritDoc}
     */
    public function initialize(): void
    {
        $this->_admin = new Admin();
    }

    /**
     * {@inheritDoc}
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        $app->addPlugin("User");
        $app->addPlugin("Bootstrap");

        EventManager::instance()->on($this);
        EventManager::instance()->on(new ActionDispatcherListener());

        if (\Cake\Core\Plugin::isLoaded('Settings')) {
            //SettingsManager::register($this->getName(), Settings::class);
            //EventManager::instance()->on(new Settings());
        }

        $this->_app = $app;
//        foreach ($this->_app->plugins()->loaded() as $pluginName) {
//            $instance = $this->_app->plugins()->get($pluginName);
//            if (method_exists($instance, 'adminBootstrap')) {
//                try {
//                    call_user_func([$instance, 'adminBootstrap'], $this->_admin);
//                } catch (\Exception $ex) {
//                    Log::error("Admin plugin bootstrapping failed: $pluginName: " . $ex->getMessage());
//                }
//            }
//        }
    }

    /**
     * {@inheritDoc}
     */
    public function routes(\Cake\Routing\RouteBuilder $routes): void
    {
        parent::routes($routes);

        $routes->scope('/admin/admin/', ['prefix' => 'Admin', 'plugin' => 'Admin', '_namePrefix' => 'admin:admin:'], function ($routes) {
            /** @var \Cake\Routing\RouteBuilder $routes */
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

            // admin:admin:auth:logout
            $routes->connect(
                '/logout',
                ['controller' => 'Auth', 'action' => 'logout'],
                [ '_name' => 'user:logout']
            );

            // admin:admin:auth:user
            $routes->connect(
                '/user',
                ['controller' => 'Auth', 'action' => 'user'],
                [ '_name' => 'user:profile']
            );

            // admin:admin:dashboard
            $routes->connect(
                '/',
                ['controller' => 'Admin', 'action' => 'index'],
                ['_name' => 'dashboard']
            );

            $routes->fallbacks(DashedRoute::class);
        });

        $urlPrefix = '/' . trim(Admin::$urlPrefix, '/') . '/';
        foreach ($this->_app->getPlugins()->with('routes') as $instance) {
            //$instance = $this->_app->getPlugins()->get($pluginName);
            $pluginName = $instance->getName();
            if (method_exists($instance, 'adminRoutes')) {
                try {
                    Router::scope($urlPrefix . Inflector::underscore($pluginName), [
                        'plugin' => $pluginName,
                        'prefix' => 'Admin',
                        '_namePrefix' => sprintf("admin:%s:", Inflector::underscore($pluginName)),
                    ], [$instance, 'adminRoutes']);
                } catch (\Exception $ex) {
                    Log::error("Admin plugin loading failed: $pluginName: " . $ex->getMessage());
                }
            } else {
//                try {
//                    Router::scope($urlPrefix . Inflector::underscore($pluginName), [
//                        'plugin' => $pluginName,
//                        'prefix' => 'Admin',
//                        '_namePrefix' => sprintf("admin:%s:", Inflector::underscore($pluginName))
//                    ], function(RouteBuilder $routes) {
//                        $routes->fallbacks('DashedRoute');
//                    });
//                } catch (\Exception $ex) {
//                    Log::error("Admin plugin loading failed: $pluginName: " . $ex->getMessage());
//                }
            }
        }

        $event = $this->dispatchEvent('Admin.Routes.setup', ['routes' => $routes]);
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
    public function buildAdminMenu(Event $event, \Banana\Menu\Menu $menu)
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
    public function buildAdminSystemMenu(Event $event, \Banana\Menu\Menu $menu)
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
