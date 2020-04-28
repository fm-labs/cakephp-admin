<?php
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\Routing\Router;

/**
 * Configurable route path
 * Use 'Admin.path' config key
 */
//$path = Configure::read('Admin.path');
//if (!$path || !preg_match('/^\/(.*)$/', $path)) {
//    $path = '/admin';
//}
//
//Router::scope('/admin/admin', ['_namePrefix' => 'admin:admin:', 'prefix' => 'Admin', 'plugin' => 'Admin'], function ($routes) {
//
//    //$routes->extensions(['json']);
//
//    // admin:admin:auth:login
//    $routes->connect(
//        '/login',
//        ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'login'],
//        ['_name' => 'user:login']
//    );
//
//    // admin:admin:auth:logout
//    $routes->connect(
//        '/logout',
//        ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'logout'],
//        [ '_name' => 'user:logout']
//    );
//
//    // admin:admin:auth:user
//    $routes->connect(
//        '/user',
//        ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'user'],
//        [ '_name' => 'user:profile']
//    );
//
//    // admin:admin:dashboard
//    $routes->connect(
//        '/',
//        ['plugin' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'],
//        ['_name' => 'dashboard']
//    );
//
//    // Fallbacks
//    //$routes->connect('/:controller');
//    $routes->fallbacks('DashedRoute');
//});


/**
 * Admin routes
 */
//Router::extensions(['json', 'xml']);
//Router::scope('/admin', ['_namePrefix' => 'admin:admin:', 'prefix' => 'Admin', 'plugin' => 'Admin'], function($routes) {
//
//    // admin:admin:auth:login
//    $routes->connect(
//        '/login',
//        ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'login'],
//        ['_name' => 'user:login']
//    );
//    $routes->connect(
//        '/login-success',
//        ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'loginSuccess'],
//        ['_name' => 'user:loginsuccess']
//    );
//
//    // admin:admin:auth:logout
//    $routes->connect(
//        '/logout',
//        ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'logout'],
//        [ '_name' => 'user:logout']
//    );
//
//    // admin:admin:auth:user
//    $routes->connect(
//        '/user',
//        ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'user'],
//        [ '_name' => 'user:profile']
//    );
//
//    // admin:admin:dashboard
//    $routes->connect(
//        '/',
//        ['plugin' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'],
//        ['_name' => 'dashboard']
//    );
//
//    // Fallbacks
//    //$routes->connect('/:controller/:action');
//    //$routes->connect('/:controller');
//    $routes->fallbacks('DashedRoute');
//
//});

//Router::scope($path, [ /*'path' => $path, */ '_namePrefix' => 'admin:admin:', 'prefix' => 'Admin', 'plugin' => 'Admin'], function (\Cake\Routing\RouteBuilder $routes) {
//
//    $routes->connect('/', ['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index']);
//
//    //$routes->scope('/admin', ['_namePrefix' => 'admin:', 'prefix' => 'Admin'], function ($routes) {
//
//        //$routes->extensions(['json']);
//
//        // admin:admin:auth:login
//        $routes->connect(
//            '/login',
//            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'login'],
//            ['_name' => 'user:login']
//        );
//
//        // admin:admin:auth:logout
//        $routes->connect(
//            '/logout',
//            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'logout'],
//            [ '_name' => 'user:logout']
//        );
//
//        // admin:admin:auth:user
//        $routes->connect(
//            '/user',
//            ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'user'],
//            [ '_name' => 'user:profile']
//        );
//
//        // admin:admin:dashboard
//        $routes->connect(
//            '/',
//            ['plugin' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'],
//            ['_name' => 'dashboard']
//        );
//
//        // Fallbacks
//        //$routes->connect('/:controller');
//        $routes->fallbacks('DashedRoute');
//    //});
//});
