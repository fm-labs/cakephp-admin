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
            ['_name' => 'auth:login']
        );

        // backend:admin:auth:logout
        $routes->connect(
            '/logout',
            ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout'],
            [ '_name' => 'auth:logout']
        );

        // backend:admin:auth:user
        $routes->connect(
            '/user',
            ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'user'],
            [ '_name' => 'auth:user']
        );

        // backend:admin:master
        $routes->connect(
            '/',
            ['plugin' => 'Backend', 'controller' => 'Master', 'action' => 'index'],
            ['_name' => 'master']
        );

        // Fallbacks
        $routes->connect('/:controller');
        $routes->fallbacks('DashedRoute');
    });
});

/**
 * Fallback routes for app backend
 * @TODO Use a configuration param to enable/disable fallback routes for app's admin prefixed routes
 */

// backend:admin:dashboard
$dashboardUrl = (Configure::read('Backend.Dashboard.url'))
    ?: ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'index'];
Router::connect('/dashboard', $dashboardUrl, ['_name' => 'admin:dashboard']);

Router::connect('/admin/:controller/:action/*', ['prefix' => 'admin']);
Router::connect('/admin/:controller/:action', ['prefix' => 'admin']);
Router::connect('/admin/:controller', ['prefix' => 'admin', 'action' => 'index']);