<?php
use Cake\Routing\Router;

Router::plugin('Backend', function ($routes) {
    $routes->prefix('admin', function ($routes) {

        $routes->connect('/media/', ['controller' => 'MediaBrowser', 'action' => 'index', 'config' => 'default']);
        $routes->connect('/media/:config/', ['controller' => 'MediaBrowser', 'action' => 'index']);
        $routes->connect('/media/:config/:action', ['controller' => 'MediaBrowser']);

        $routes->connect('/:controller');
        $routes->fallbacks('DashedRoute');
    });
});
