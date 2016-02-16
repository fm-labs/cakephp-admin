<?php
use Cake\Routing\Router;

Router::plugin('Backend', function ($routes) {
    $routes->prefix('admin', function ($routes) {

        $routes->extensions(['json']);

        $routes->connect('/:controller');
        $routes->fallbacks('DashedRoute');
    });
});
