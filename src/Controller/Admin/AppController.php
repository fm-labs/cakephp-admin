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
                'icon' => 'setting',

                '_children' => [
                    'dashboard' => [
                        'title' => 'Dashboard',
                        'url' => ['plugin' => 'Backend', 'controller' => 'Backend', 'action' => 'index'],
                        'icon' => 'dashboard'
                    ],
                    'logs' => [
                        'title' => 'Logs',
                        'url' => ['plugin' => 'Backend', 'controller' => 'Logs', 'action' => 'index'],
                        'icon' => 'browser'
                    ],
                    'files' => [
                        'title' => 'Files',
                        'url' => ['plugin' => 'Backend', 'controller' => 'FileManager', 'action' => 'index'],
                        'icon' => 'file'
                    ],
                    'system' => [
                        'title' => 'Systeminfo',
                        'url' => ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                        'icon' => 'info'
                    ],
                    'users' => [
                        'title' => 'Access Controller',
                        'url' => ['plugin' => 'Backend', 'controller' => 'Users', 'action' => 'index'],
                        'icon' => 'lock'
                    ]
                ]
            ]
        ];
    }
}
