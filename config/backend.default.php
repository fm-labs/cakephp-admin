<?php
/**
 * Copy backend.default.php to your app's config folder,
 * rename to backend.php and adjust contents
 */

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
    'Backend.Dashboard.title' => 'Backend',
    'Backend.Dashboard.url' => ['plugin' => 'Backend', 'controller' => 'Backend', 'action' => 'index'],

    /**
     * Backend Security
     *
     * - enabled: Enables SecurityComponent
     * - forceSSL: Force https scheme for all backend requests
     */
    'Backend.Security.enabled' => false,
    'Backend.Security.forceSSL' => false,

    /**
     * Backend AuthComponent config
     */
    'Backend.Auth' => [],

    /**
     * Backend Search config
     */
    'Backend.Search.searchUrl' => ['plugin' => 'Backend', 'controller' => 'Search', 'action' => 'index'],

    /**
     * Backend AdminLTE theme options
     */
    //'Backend.AdminLte.skin_class' => 'skin-blue',
    //'Backend.AdminLte.layout_class' => '',
    //'Backend.AdminLte.sidebar_class' => 'sidebar-mini',
];
