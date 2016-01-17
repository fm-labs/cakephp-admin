<?php
use Cake\Routing\Router;

Router::plugin('Backend', function ($routes) {
    $routes->prefix('admin', function ($routes) {

        $routes->extensions(['json']);

        $routes->connect('/media/', ['controller' => 'MediaBrowser', 'action' => 'tree', 'config' => 'default']);
        $routes->connect('/media/:config/', ['controller' => 'MediaBrowser', 'action' => 'tree']);
        $routes->connect('/media/:config/:action', ['controller' => 'MediaBrowser']);


        $routes->connect('/:controller');
        $routes->fallbacks('DashedRoute');
    });
});
