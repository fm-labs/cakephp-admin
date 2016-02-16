<?php
return [
    /**
     * Backend Dashboard
     *
     * - title: Dashboard title string
     * - url: Url to Dashboard
     */
    'Backend.Dashboard' => [
        'title' => 'Backend',
        'url' => '/backend/admin/Default/index',
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
];
