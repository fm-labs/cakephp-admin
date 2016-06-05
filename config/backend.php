<?php
return [

    /**
     * Backend plugin route path
     */
    'Backend.path' => '/backend',

    /**
     * Backend theme name
     */
    'Backend.theme' => null,

    /**
     * Backend Dashboard
     *
     * - title: Dashboard title string
     * - url: Url to Dashboard
     */
    'Backend.Dashboard' => [
        'title' => 'Backend',
        'url' => '/backend/admin/Dashboard/index',
    ],

    /**
     * Backend Security
     *
     * - enabled: Enables SecurityComponent
     * - forceSSL: Force https scheme for all backend requests
     */
    'Backend.Security' => [
        'enabled' => false,
        'forceSSL' => false
    ],

    /**
     * Backend AuthComponent config
     */
    'Backend.Auth' => [
    ],

    /**
     * Backend Basic Auth Users
     */
    'Backend.Users' => [
        //'admin' => 'myAdminPa$$w0rd'
    ],

    'Backend.Plugin.Backend.Menu' => [
        //'plugin' => 'Backend',
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
