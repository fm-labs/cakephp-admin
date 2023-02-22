<?php
/**
 * Default configuration for Admin plugin
 */

return [

    'Admin.version' => '2.0.3',

    /**
     * Admin plugin route path
     */
    //'Admin.path' => '/admin',

    /**
     * Admin theme name
     */
    //'Admin.theme' => null,

    /**
     * Admin Dashboard
     *
     * - title: Dashboard title string
     * - url: Url to Dashboard
     */
    'Admin.Dashboard.title' => __d('admin', 'Administration'),
    'Admin.Dashboard.url' => ['plugin' => 'Admin', 'controller' => 'Admin', 'action' => 'index'],
    'Admin.Dashboard.panels' => [],

    /**
     * Admin Security
     *
     * - enabled: Enables SecurityComponent
     * - forceSSL: Force https scheme for all admin requests
     */
    //'Admin.Security.enabled' => false,
    //'Admin.Security.forceSSL' => false,

    /**
     * Admin AuthComponent config
     */
    //'Admin.Auth' => [],

    /**
     * Admin Search config
     */
    //'Admin.Search.searchUrl' => ['plugin' => 'Admin', 'controller' => 'Search', 'action' => 'index'],
];
