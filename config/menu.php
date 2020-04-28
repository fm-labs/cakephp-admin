<?php
return [
    'admin' => [
        'plugin' => 'Admin',
        'title' => 'System',
        'url' => ['plugin' => 'Admin', 'controller' => 'Dashboard', 'action' => 'admin'],
        'data-icon' => 'cube',
        // 'requireRoot' => true, // temporary access control workaround

        '_children' => [
            'system' => [
                'title' => 'Systeminfo',
                'url' => ['plugin' => 'Admin', 'controller' => 'System', 'action' => 'index'],
                'data-icon' => 'info',
            ],
            'settings' => [
                'title' => 'Settings',
                'url' => ['plugin' => 'Admin', 'controller' => 'Settings', 'action' => 'index'],
                'data-icon' => 'gears',
                'requireRoot' => true, // temporary access control workaround
            ],
            'logs' => [
                'title' => 'Logs',
                'url' => ['plugin' => 'Admin', 'controller' => 'Logs', 'action' => 'index'],
                'data-icon' => 'file-text-o',
                'requireRoot' => true, // temporary access control workaround
            ],
            'users' => [
                'title' => 'Access Control',
                'url' => ['plugin' => 'User', 'controller' => 'Users', 'action' => 'index'],
                'data-icon' => 'lock',
                'requireRoot' => true, // temporary access control workaround
            ],
        ],
    ],
];
