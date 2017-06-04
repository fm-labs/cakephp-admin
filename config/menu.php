<?php
return [
    'backend' => [
        'plugin' => 'Backend',
        'title' => 'System',
        'url' => ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'backend'],
        'data-icon' => 'cube',
        // 'requireRoot' => true, // temporary access control workaround

        '_children' => [
            'system' => [
                'title' => 'Systeminfo',
                'url' => ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                'data-icon' => 'info'
            ],
            'settings' => [
                'title' => 'Settings',
                'url' => ['plugin' => 'Backend', 'controller' => 'Settings', 'action' => 'index'],
                'data-icon' => 'gears',
                'requireRoot' => true, // temporary access control workaround
            ],
            'logs' => [
                'title' => 'Logs',
                'url' => ['plugin' => 'Backend', 'controller' => 'Logs', 'action' => 'index'],
                'data-icon' => 'file-text-o',
                'requireRoot' => true, // temporary access control workaround
            ],
            'users' => [
                'title' => 'Access Control',
                'url' => ['plugin' => 'User', 'controller' => 'Users', 'action' => 'index'],
                'data-icon' => 'lock',
                'requireRoot' => true, // temporary access control workaround
            ],
        ]
    ],
];
