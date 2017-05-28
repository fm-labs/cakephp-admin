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
 * Backend routes
 */
Router::plugin('Backend', [ 'path' => $path, '_namePrefix' => 'backend:' ], function ($routes) {
    $routes->scope('/admin', ['_namePrefix' => 'admin:', 'prefix' => 'admin'], function ($routes) {

        $routes->extensions(['json']);

        // backend:admin:auth:login
        $routes->connect(
            '/login',
            ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'login'],
            ['_name' => 'user:login']
        );

        // backend:admin:auth:logout
        $routes->connect(
            '/logout',
            ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout'],
            [ '_name' => 'user:logout']
        );

        // backend:admin:auth:user
        $routes->connect(
            '/user',
            ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'user'],
            [ '_name' => 'user:profile']
        );

        // backend:admin:dashboard
        $routes->connect(
            '/',
            ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'index'],
            ['_name' => 'dashboard']
        );

        // Fallbacks
        //$routes->connect('/:controller');
        $routes->fallbacks('DashedRoute');
    });
});

/**
 * Fallback routes for app backend
 * @TODO Use a configuration param to enable/disable fallback routes for app's admin prefixed routes
 */

Router::scope('/admin', ['_namePrefix' => 'admin:', 'prefix' => 'admin'], function($routes) {
    // admin:dashboard
    $dashboardUrl = (Configure::read('Backend.Dashboard.url'))
        ?: ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'index', 'prefix' => 'admin'];
    $routes->connect('/dashboard', $dashboardUrl, ['_name' => 'dashboard']);

    $routes->extensions(['json']);

    // default admin routes
    $routes->connect('/:controller/:action/*');
    $routes->connect('/:controller/:action');
    $routes->connect('/:controller', ['action' => 'index']);

    $routes->fallbacks('DashedRoute');
});