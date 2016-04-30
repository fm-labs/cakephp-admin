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
                'url' => ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'backend'],
                'icon' => 'cube',
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
                        'icon' => 'gears',
                        'requireRoot' => true, // temporary access control workaround
                    ],
                    'logs' => [
                        'title' => 'Logs',
                        'url' => ['plugin' => 'Backend', 'controller' => 'Logs', 'action' => 'index'],
                        'icon' => 'file-text-o',
                        'requireRoot' => true, // temporary access control workaround
                    ],
                    'users' => [
                        'title' => 'Access Control',
                        'url' => ['plugin' => 'User', 'controller' => 'Users', 'action' => 'index'],
                        'icon' => 'lock',
                        'requireRoot' => true, // temporary access control workaround
                    ],
                ]
            ],
        ];
    }
}
