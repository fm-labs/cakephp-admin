<?php
use Cake\Core\Configure;
use Cake\Event\EventManager;
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
//Router::extensions(['json', 'xml']);
//Router::scope('/backend', ['_namePrefix' => 'backend:admin:', 'prefix' => 'admin', 'plugin' => 'Backend'], function($routes) {
//
//    // backend:admin:auth:login
//    $routes->connect(
//        '/login',
//        ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'login'],
//        ['_name' => 'user:login']
//    );
//    $routes->connect(
//        '/login-success',
//        ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'loginSuccess'],
//        ['_name' => 'user:loginsuccess']
//    );
//
//    // backend:admin:auth:logout
//    $routes->connect(
//        '/logout',
//        ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout'],
//        [ '_name' => 'user:logout']
//    );
//
//    // backend:admin:auth:user
//    $routes->connect(
//        '/user',
//        ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'user'],
//        [ '_name' => 'user:profile']
//    );
//
//    // backend:admin:dashboard
//    $routes->connect(
//        '/',
//        ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'index'],
//        ['_name' => 'dashboard']
//    );
//
//    // Fallbacks
//    //$routes->connect('/:controller/:action');
//    //$routes->connect('/:controller');
//    $routes->fallbacks('DashedRoute');
//
//});

//Router::scope($path, [ /*'path' => $path, */ '_namePrefix' => 'backend:admin:', 'prefix' => 'admin', 'plugin' => 'Backend'], function (\Cake\Routing\RouteBuilder $routes) {
//
//    $routes->connect('/', ['prefix' => 'admin', 'controller' => 'Dashboard', 'action' => 'index']);
//
//    //$routes->scope('/admin', ['_namePrefix' => 'admin:', 'prefix' => 'admin'], function ($routes) {
//
//        //$routes->extensions(['json']);
//
//        // backend:admin:auth:login
//        $routes->connect(
//            '/login',
//            ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'login'],
//            ['_name' => 'user:login']
//        );
//
//        // backend:admin:auth:logout
//        $routes->connect(
//            '/logout',
//            ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout'],
//            [ '_name' => 'user:logout']
//        );
//
//        // backend:admin:auth:user
//        $routes->connect(
//            '/user',
//            ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'user'],
//            [ '_name' => 'user:profile']
//        );
//
//        // backend:admin:dashboard
//        $routes->connect(
//            '/',
//            ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'index'],
//            ['_name' => 'dashboard']
//        );
//
//        // Fallbacks
//        //$routes->connect('/:controller');
//        $routes->fallbacks('DashedRoute');
//    //});
//});


