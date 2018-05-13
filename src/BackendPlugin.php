<?php

namespace Backend;

use Backend\Event\RouteBuilderEvent;
use Backend\Backend;
use Backend\View\BackendView;
use Banana\Menu\Menu;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Settings\SettingsManager;

class BackendPlugin implements EventListenerInterface
{

    use EventDispatcherTrait;

    /**
     * @var Backend Instance of Backend
     */
    protected $backend;

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
            'Settings.build'        => 'buildSettings',
            'Backend.Sidebar.build' => ['callable' => 'buildBackendSidebarMenu', 'priority' => 99 ],
            'Backend.SysMenu.build' => ['callable' => 'buildBackendSystemMenu', 'priority' => 99 ],
            'Backend.Menu.build'    => ['callable' => 'buildBackendMenu', 'priority' => 99 ],
            'Backend.Routes.build'  => ['callable' => 'buildBackendRoutes', 'priority' => 1 ],
            'View.beforeLayout'     => ['callable' => 'beforeLayout']
        ];
    }

    public function beforeLayout(Event $event)
    {
        if ($event->subject() instanceof BackendView && $event->subject()->plugin == "Backend") {
            $menu = new Menu($this->_getMenuItems());
            $event->subject()->set('backend.sidebar.menu', $menu);
        }
    }

    /**
     * @param Event $event
     */
    public function buildSettings(Event $event)
    {
        if ($event->subject() instanceof SettingsManager) {
            $event->subject()->add('Backend', [
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

                'AdminLte.skin_class' => [
                    'type' => 'string',
                    'input' => [
                        'type' => 'select',
                        'options' => [
                            'skin-blue'     => 'Blue',
                            'skin-yellow'   => 'Yellow',
                            'skin-red'      => 'Red',
                            'skin-purple'   => 'Purple',
                            'skin-black'    => 'Blue',
                            'skin-green'    => 'Green',
                        ]
                    ],
                    'default' => 'skin-blue'
                ],

                'AdminLte.layout_class' => [
                    'type' => 'string',
                    'input' => [
                        'type' => 'select',
                        'empty' => true,
                        'options' => [
                            'fixed'             => 'Fixed',
                            'layout-boxed'      => 'Layout Boxed',
                            'layout-top-nav'    => 'Layout Top Nav',
                        ]
                    ],
                    'default' => null
                ],

                'AdminLte.sidebar_class' => [
                    'type' => 'string',
                    'input' => [
                        'type' => 'select',
                        'empty' => true,
                        'options' => [
                            'sidebar-mini' => 'Sidebar Mini',
                            'sidebar-mini sidebar-collapse' => 'Sidebar Mini Collapsed',
                        ]
                    ],
                    'default' => null
                ]
            ]);
        }
    }

    public function buildBackendRoutes(RouteBuilderEvent $event)
    {
//        $event->subject()->scope('/backend',
//            ['_namePrefix' => 'backend:admin:', 'prefix' => 'admin', 'plugin' => 'Backend'], function(RouteBuilder $routes) {
//
//            // backend:admin:auth:login
//            $routes->connect(
//                '/login',
//                ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'login'],
//                ['_name' => 'user:login']
//            );
//            $routes->connect(
//                '/login-success',
//                ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'loginSuccess'],
//                ['_name' => 'user:loginsuccess']
//            );
//
//            // backend:admin:auth:logout
//            $routes->connect(
//                '/logout',
//                ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout'],
//                [ '_name' => 'user:logout']
//            );
//
//            // backend:admin:auth:user
//            $routes->connect(
//                '/user',
//                ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'user'],
//                [ '_name' => 'user:profile']
//            );
//
//            // backend:admin:dashboard
//            $routes->connect(
//                '/',
//                ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'index'],
//                ['_name' => 'dashboard']
//            );
//
//            // Fallbacks
//            //$routes->connect('/:controller/:action');
//            //$routes->connect('/:controller');
//            $routes->fallbacks('DashedRoute');
//
//        });
//
//
//        $dashboardUrl = (Configure::read('Backend.Dashboard.url'))
//            ?: ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'index', 'prefix' => 'admin'];
//        $event->subject()->connect('/dashboard', $dashboardUrl, ['_name' => 'dashboard']);

//        /**
//         * Fallback routes for app backend
//         * @TODO Use a configuration param to enable/disable fallback routes for app's admin prefixed routes
//         * @TODO Move to separate (high priority) event listener
//         */
//        Router::scope('/admin', ['_namePrefix' => 'admin:', 'prefix' => 'admin'], function ($routes) {
//
//            // default admin routes
//            $routes->extensions(['json']);
//            $routes->fallbacks('DashedRoute');
//        });
    }


    /**
     * @param Event $event
     */
    public function buildBackendSidebarMenu(Event $event)
    {
        if ($event->subject() instanceof \Banana\Menu\Menu) {
//            $settingsMenu = new Menu();
//            $this->eventManager()->dispatch(new Event('Backend.SysMenu.build', $settingsMenu));
//            $event->subject()->addItem([
//                'title' => 'System',
//                'url' => ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
//                'data-icon' => 'gears',
//                'children' => $settingsMenu->getItems(),
//            ]);
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
            ],
            'settings' => [
                'title' => 'Settings',
                'url' => ['plugin' => 'Backend', 'controller' => 'Settings', 'action' => 'manage'],
                'data-icon' => 'sliders',
            ]
        ];
    }

    public function __invoke()
    {
    }
}
