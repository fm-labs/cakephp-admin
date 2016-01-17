<?php

namespace Backend\Controller\Admin;

class AppController extends AbstractBackendController
{

    public static function backendMenu()
    {
        return [
            'Backend' => [
                'plugin' => 'Backend',
                'title' => 'Backend',
                'url' => ['plugin' => 'Backend', 'controller' => 'Backend', 'action' => 'index'],
                'icon' => 'cubes',

                '_children' => [
                    'media' => [
                        'title' => 'Media',
                        'url' => ['plugin' => 'Backend', 'controller' => 'MediaBrowser', 'action' => 'index'],
                        'icon' => 'file'
                    ],
                    'settings' => [
                        'title' => 'Settings',
                        'url' => ['plugin' => 'Backend', 'controller' => 'Settings', 'action' => 'index'],
                        'icon' => 'settings'
                    ],
                    'system' => [
                        'title' => 'Systeminfo',
                        'url' => ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                        'icon' => 'info'
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
