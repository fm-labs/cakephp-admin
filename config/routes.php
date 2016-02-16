<?php
use Cake\Core\Configure;
use Cake\Routing\Router;

$path = Configure::read('Backend.path');
if (!$path || !preg_match('/^\/(.*)$/', $path)) {
    $path = '/backend';
}

Router::plugin('Backend', [ 'path' => $path ], function ($routes) {
    $routes->prefix('admin', function ($routes) {

        $routes->extensions(['json']);

        // Connect auth
        $routes->connect('/login', ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'login']);
        $routes->connect('/logout', ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout']);

        // Connect dashboard
        $dashboardUrl = Configure::read('Backend.Dashboard.url');
        if (!$dashboardUrl) {
            $dashboardUrl = ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'index'];
        }
        $routes->connect('/', $dashboardUrl);

        // Fallbacks
        $routes->connect('/:controller');
        $routes->fallbacks('DashedRoute');
    });
});
