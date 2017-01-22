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
        'url' => ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'index'],
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

    'Backend.Search' => [
        'searchUrl' => ['plugin' => 'Backend', 'controller' => 'Search', 'action' => 'index'],
    ],

    /**
     * Backend Basic Auth Users
     */
    'Backend.Users' => [
        //'admin' => 'myAdminPa$$w0rd'
    ],

    'Backend.Menu' => [
        'title' => 'System',
        'url' => ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'backend'],
        'data-icon' => 'cube',

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
        ]
    ],

];
