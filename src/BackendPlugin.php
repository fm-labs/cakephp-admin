<?php

namespace Backend;

use Backend\Event\RouteBuilderEvent;
use Backend\Backend;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Routing\Router;
use Settings\SettingsManager;

class BackendPlugin implements EventListenerInterface
{

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
            'Settings.build' => 'buildSettings',
            'Backend.Menu.get' => ['callable' => 'getBackendMenu', 'priority' => 99 ],
            'Backend.Routes.build' => ['callable' => 'buildBackendRoutes', 'priority' => 99 ]
        ];
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
                            'sidebar-mini'     => 'Sidebar Mini',
                            'sidebar-collapse'     => 'Sidebar Collapse',
                        ]
                    ],
                    'default' => null
                ]
            ]);
        }
    }

    public function getSettings(Event $event)
    {
        $event->result['Backend'] = [
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
                'default' => 'skin-blue'
            ],

            'AdminLte.sidebar_class' => [
                'type' => 'string',
                'input' => [
                    'type' => 'select',
                    'empty' => true,
                    'options' => [
                        'sidebar-mini'     => 'Sidebar Mini',
                        'sidebar-collapse'     => 'Sidebar Collapse',
                    ]
                ],
                'default' => 'skin-blue'
            ]
        ];
    }

    public function buildBackendRoutes(RouteBuilderEvent $event)
    {
        // admin:dashboard
        $dashboardUrl = (Configure::read('Backend.Dashboard.url'))
            ?: ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'index', 'prefix' => 'admin'];
        Router::connect('/dashboard', $dashboardUrl, ['_name' => 'dashboard']);

        /**
         * Fallback routes for app backend
         * @TODO Use a configuration param to enable/disable fallback routes for app's admin prefixed routes
         * @TODO Move to separate (high priority) event listener
         */
        Router::scope('/admin', ['_namePrefix' => 'admin:', 'prefix' => 'admin'], function ($routes) {

            // default admin routes
            $routes->extensions(['json']);
            $routes->fallbacks('DashedRoute');
        });
    }

    public function getBackendMenu(Event $event)
    {
        $event->subject()->addItem([
            'title' => 'System',
            'url' => ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'backend'],
            'data-icon' => 'gears',

            'children' => [
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
                    'url' => ['plugin' => 'User', 'controller' => 'Users', 'action' => 'index'],
                    'data-icon' => 'users',
                ],
            ]
        ]);
    }

    public function __invoke()
    {
        $this->backend = new Backend(EventManager::instance(), []);
        $this->backend->loadServices();
        $this->backend->initializeServices();
    }
}
