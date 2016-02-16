<?php

namespace Backend\Controller\Admin;

class AppController extends AbstractBackendController
{

    public static function backendMenu()
    {
        return [
            'plugin.backend' => [
                'plugin' => 'Backend',
                'title' => 'System',
                'url' => ['plugin' => 'Backend', 'controller' => 'Backend', 'action' => 'index'],
                'icon' => 'cubes',
               // 'requireRoot' => true, // temporary access control workaround

                '_children' => [
                    'system' => [
                        'title' => 'Systeminfo',
                        'url' => ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                        'icon' => 'info'
                    ],
                    'settings' => [
                        'title' => 'Settings',
                        'url' => ['plugin' => 'Backend', 'controller' => 'Settings', 'action' => 'index'],
                        'icon' => 'settings',
                        'requireRoot' => true, // temporary access control workaround
                    ],
                    'logs' => [
                        'title' => 'Logs',
                        'url' => ['plugin' => 'Backend', 'controller' => 'Logs', 'action' => 'index'],
                        'icon' => 'browser',
                        'requireRoot' => true, // temporary access control workaround
                    ],
                    'users' => [
                        'title' => 'Users',
                        'url' => ['plugin' => 'User', 'controller' => 'Users', 'action' => 'index'],
                        'icon' => 'lock',
                        'requireRoot' => true, // temporary access control workaround
                    ],
                ]
            ],
        ];
    }
}
