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

                '_children' => [
                    'system' => [
                        'title' => 'Systeminfo',
                        'url' => ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                        'icon' => 'info'
                    ],
                    'settings' => [
                        'title' => 'Settings',
                        'url' => ['plugin' => 'Backend', 'controller' => 'Settings', 'action' => 'index'],
                        'icon' => 'settings'
                    ],
                    'logs' => [
                        'title' => 'Logs',
                        'url' => ['plugin' => 'Backend', 'controller' => 'Logs', 'action' => 'index'],
                        'icon' => 'browser'
                    ],
                    'users' => [
                        'title' => 'Users',
                        'url' => ['plugin' => 'Backend', 'controller' => 'Users', 'action' => 'index'],
                        'icon' => 'lock'
                    ],
                ]
            ],
        ];
    }
}
