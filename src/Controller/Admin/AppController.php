<?php

namespace Backend\Controller\Admin;

class AppController extends AbstractBackendController
{

    public static function backendMenu()
    {
        return [
            'backend' => [
                'plugin' => 'Backend',
                'title' => 'Backend',
                'url' => ['plugin' => 'Backend', 'controller' => 'Backend', 'action' => 'index'],
                'icon' => 'cubes',

                '_children' => [
                    'logs' => [
                        'title' => 'Logs',
                        'url' => ['plugin' => 'Backend', 'controller' => 'Logs', 'action' => 'index'],
                        'icon' => 'info'
                    ],
                    'users' => [
                        'title' => 'Users',
                        'url' => ['plugin' => 'Backend', 'controller' => 'Users', 'action' => 'index'],
                        'icon' => 'info'
                    ],
                    'files' => [
                        'title' => 'File Manager',
                        'url' => ['plugin' => 'Backend', 'controller' => 'FileManager', 'action' => 'index'],
                        'icon' => 'info'
                    ],
                    'system' => [
                        'title' => 'Systeminfo',
                        'url' => ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                        'icon' => 'info'
                    ]
                ]
            ]
        ];
    }
}
