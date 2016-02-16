<?php
use Cake\Core\Configure;
use Cake\Routing\Router;

$path = Configure::read('Backend.path');
if (!$path || !preg_match('/^\/(.*)$/', $path)) {
    die('Invalid backend path: ' . $path);
    $path = '/backend';
}

Router::plugin('Backend', [ 'path' => $path ], function ($routes) {
    $routes->prefix('admin', function ($routes) {

        $routes->extensions(['json']);

        // User auth routes
        Router::connect('/login', ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'login']);
        Router::connect('/logout', ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout']);

        $routes->connect('/:controller');
        $routes->fallbacks('DashedRoute');
    });
});
