<?php

namespace Backend;

use Backend\Event\RouteBuilderEvent;
use Backend\Backend;
use Backend\View\BackendView;
use Banana\Application;
use Banana\Menu\Menu;
use Banana\Plugin\PluginInterface;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Settings\SettingsInterface;
use Settings\SettingsManager;

class BackendPlugin implements PluginInterface, BackendPluginInterface, SettingsInterface, EventListenerInterface
{
    /**
     * Returns a list of events this object is implementing. When the class is registered
     * in an event manager, each individual method will be associated with the respective event.
     *
     * @see EventListenerInterface::implementedEvents()
     * @return array associative array or event key names pointing to the function
     * that should be called in the object when the respective event is fired
     */
    public function implementedEvents()
    {
        return [
            'Backend.Sidebar.build' => ['callable' => 'buildBackendSidebarMenu', 'priority' => 99 ],
            //'Backend.SysMenu.build' => ['callable' => 'buildBackendSystemMenu', 'priority' => 99 ],
            //'Backend.Menu.build'    => ['callable' => 'buildBackendMenu', 'priority' => 99 ],
           // 'View.beforeLayout'     => ['callable' => 'beforeLayout']
        ];
    }

    public function beforeLayout(Event $event)
    {
        if ($event->subject() instanceof BackendView && $event->subject()->plugin == "Backend") {
            $menu = new Menu($this->_getMenuItems());
            $event->subject()->set('backend.sidebar.menu', $menu);
        }
    }

    public function buildSettings(SettingsManager $settings)
    {
        //$settings->load('Backend.settings');
        $settings->add('Backend', [
            'Dashboard.title' => [
                'type' => 'string',
                'input' => [
                    'type' => 'text',
                    'placeholder' => 'Foo'
                ],
                'default' => 'Backend'
            ],
            'Security.enabled' => [
                'type' => 'boolean',
                'inputType' => null,
                'input' => [
                    'placeholder' => 'Foo'
                ],
            ],

//            'AdminLte.skin_class' => [
//                'type' => 'string',
//                'input' => [
//                    'type' => 'select',
//                    'options' => [
//                        'skin-blue'     => 'Blue',
//                        'skin-yellow'   => 'Yellow',
//                        'skin-red'      => 'Red',
//                        'skin-purple'   => 'Purple',
//                        'skin-black'    => 'Blue',
//                        'skin-green'    => 'Green',
//                    ]
//                ],
//                'default' => 'skin-blue'
//            ],
//
//            'AdminLte.layout_class' => [
//                'type' => 'string',
//                'input' => [
//                    'type' => 'select',
//                    'empty' => true,
//                    'options' => [
//                        'fixed'             => 'Fixed',
//                        'layout-boxed'      => 'Layout Boxed',
//                        'layout-top-nav'    => 'Layout Top Nav',
//                    ]
//                ],
//                'default' => null
//            ],
//
//            'AdminLte.sidebar_class' => [
//                'type' => 'string',
//                'input' => [
//                    'type' => 'select',
//                    'empty' => true,
//                    'options' => [
//                        'sidebar-mini' => 'Sidebar Mini',
//                        'sidebar-mini sidebar-collapse' => 'Sidebar Mini Collapsed',
//                    ]
//                ],
//                'default' => null
//            ]
        ]);
    }


    /**
     * @param Event $event
     */
    public function buildBackendSidebarMenu(Event $event)
    {
        if ($event->subject() instanceof \Banana\Menu\Menu) {
            //$settingsMenu = new Menu();
            //$this->eventManager()->dispatch(new Event('Backend.SysMenu.build', $settingsMenu));
            $event->subject()->addItem([
                'title' => 'System',
                'url' => ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                'data-icon' => 'gears',
                'children' => $this->_getMenuItems(),
            ]);
        }
    }

    public function buildBackendMenu(Event $event)
    {
        if ($event->subject() instanceof \Banana\Menu\Menu) {}
    }

    public function buildBackendSystemMenu(Event $event)
    {
        if ($event->subject() instanceof \Banana\Menu\Menu) {
            $items = $this->_getMenuItems();
            foreach ($items as $item) {
                $event->subject()->addItem($item);
            }
        }
    }

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
            'users' => [
                'title' => 'Users',
                'url' => ['plugin' => 'Backend', 'controller' => 'Users', 'action' => 'index'],
                'data-icon' => 'users',
            ],
            'plugins' => [
                'title' => 'Plugins',
                'url' => ['plugin' => 'Backend', 'controller' => 'Plugins', 'action' => 'index'],
                'data-icon' => 'puzzle-piece',
            ]
        ];
    }

    public function routes(RouteBuilder $routes)
    {
        return $routes;
    }

    public function bootstrap(Application $app)
    {
        EventManager::instance()->on($this);
    }

    public function backendBootstrap(Backend $backend)
    {
        $backend->hook('backend.menu.build', function(Menu $menu) {
            $menu->addItem([
                'title' => 'System',
                'url' => ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                'data-icon' => 'gears',
                'children' => $this->_getMenuItems(),
            ]);
        });
    }

    public function backendRoutes(RouteBuilder $routes)
    {
        $routes->connect(
            '/login',
            ['controller' => 'Auth', 'action' => 'login'],
            ['_name' => 'user:login']
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

    public function middleware(MiddlewareQueue $middleware)
    {

    }
}
