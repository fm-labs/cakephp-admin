<?php
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\Routing\Router;

/**
 * Configurable route path
 * Use 'Backend.path' config key
 */
//$path = Configure::read('Backend.path');
//if (!$path || !preg_match('/^\/(.*)$/', $path)) {
//    $path = '/backend';
//}
//
//Router::scope('/admin/backend', ['_namePrefix' => 'admin:backend:', 'prefix' => 'admin', 'plugin' => 'Backend'], function ($routes) {
//
//    //$routes->extensions(['json']);
//
//    // admin:backend:auth:login
//    $routes->connect(
//        '/login',
//        ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'login'],
//        ['_name' => 'user:login']
//    );
//
//    // admin:backend:auth:logout
//    $routes->connect(
//        '/logout',
//        ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout'],
//        [ '_name' => 'user:logout']
//    );
//
//    // admin:backend:auth:user
//    $routes->connect(
//        '/user',
//        ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'user'],
//        [ '_name' => 'user:profile']
//    );
//
//    // admin:backend:dashboard
//    $routes->connect(
//        '/',
//        ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'index'],
//        ['_name' => 'dashboard']
//    );
//
//    // Fallbacks
//    //$routes->connect('/:controller');
//    $routes->fallbacks('DashedRoute');
//});


/**
 * Backend routes
 */
//Router::extensions(['json', 'xml']);
//Router::scope('/backend', ['_namePrefix' => 'admin:backend:', 'prefix' => 'admin', 'plugin' => 'Backend'], function($routes) {
//
//    // admin:backend:auth:login
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
//    // admin:backend:auth:logout
//    $routes->connect(
//        '/logout',
//        ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout'],
//        [ '_name' => 'user:logout']
//    );
//
//    // admin:backend:auth:user
//    $routes->connect(
//        '/user',
//        ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'user'],
//        [ '_name' => 'user:profile']
//    );
//
//    // admin:backend:dashboard
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

//Router::scope($path, [ /*'path' => $path, */ '_namePrefix' => 'admin:backend:', 'prefix' => 'admin', 'plugin' => 'Backend'], function (\Cake\Routing\RouteBuilder $routes) {
//
//    $routes->connect('/', ['prefix' => 'admin', 'controller' => 'Dashboard', 'action' => 'index']);
//
//    //$routes->scope('/admin', ['_namePrefix' => 'admin:', 'prefix' => 'admin'], function ($routes) {
//
//        //$routes->extensions(['json']);
//
//        // admin:backend:auth:login
//        $routes->connect(
//            '/login',
//            ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'login'],
//            ['_name' => 'user:login']
//        );
//
//        // admin:backend:auth:logout
//        $routes->connect(
//            '/logout',
//            ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout'],
//            [ '_name' => 'user:logout']
//        );
//
//        // admin:backend:auth:user
//        $routes->connect(
//            '/user',
//            ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'user'],
//            [ '_name' => 'user:profile']
//        );
//
//        // admin:backend:dashboard
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
