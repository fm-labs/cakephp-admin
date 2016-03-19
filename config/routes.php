<?php
use Cake\Core\Configure;
use Cake\Routing\Router;

/**
 * Configurable route path
 * Use 'Backend.path' config key
 */
$path = Configure::read('Backend.path');
if (!$path || !preg_match('/^\/(.*)$/', $path)) {
    $path = '/backend';
}

/**
 * Connect Backend routes
 */
Router::plugin('Backend', [ 'path' => $path, '_namePrefix' => 'backend:' ], function ($routes) {
    $routes->scope('/admin', ['_namePrefix' => 'admin:', 'prefix' => 'admin'], function ($routes) {

        $routes->extensions(['json']);

        // backend:admin:auth:login
        $routes->connect(
            '/login',
            ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'login'],
            ['_name' => 'auth:login']
        );
        // backend:admin:auth:logout
        $routes->connect(
            '/logout',
            ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout'],
            [ '_name' => 'auth:logout']
        );

        // backend:admin:dashboard
        $dashboardUrl = (Configure::read('Backend.Dashboard.url'))
            ?: ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'index'];
        $routes->connect('/', $dashboardUrl, ['_name' => 'dashboard']);

        // Fallbacks
        $routes->connect('/:controller');
        $routes->fallbacks('DashedRoute');
    });
});
