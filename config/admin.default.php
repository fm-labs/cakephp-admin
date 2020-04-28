<?php
/**
 * Copy admin.default.php to your app's config folder,
 * rename to admin.php and adjust contents
 */

return [

    /**
     * Admin plugin route path
     */
    'Admin.path' => '/admin',

    /**
     * Admin theme name
     */
    'Admin.theme' => null,

    /**
     * Admin Dashboard
     *
     * - title: Dashboard title string
     * - url: Url to Dashboard
     */
    'Admin.Dashboard.title' => 'Admin',
    'Admin.Dashboard.url' => ['plugin' => 'Admin', 'controller' => 'Admin', 'action' => 'index'],

    /**
     * Admin Security
     *
     * - enabled: Enables SecurityComponent
     * - forceSSL: Force https scheme for all admin requests
     */
    'Admin.Security.enabled' => false,
    'Admin.Security.forceSSL' => false,

    /**
     * Admin AuthComponent config
     */
    'Admin.Auth' => [],

    /**
     * Admin Search config
     */
    'Admin.Search.searchUrl' => ['plugin' => 'Admin', 'controller' => 'Search', 'action' => 'index'],

    /**
     * Admin AdminLTE theme options
     */
    //'Admin.AdminLte.skin_class' => 'skin-blue',
    //'Admin.AdminLte.layout_class' => '',
    //'Admin.AdminLte.sidebar_class' => 'sidebar-mini',
];
