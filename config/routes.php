<?php
use Cake\Routing\Router;

Router::plugin('Backend', function ($routes) {
    $routes->prefix('admin', function ($routes) {
        $routes->connect('/:controller');
        $routes->fallbacks();
    });
    $routes->fallbacks();
});
