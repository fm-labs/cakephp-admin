<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 2/13/16
 * Time: 4:51 PM
 */

namespace Backend\Lib;


class BackendNav
{
    static public function getMenu()
    {
        $menu = [];
        $plugins = Backend::plugins();

        //check each plugin's AppController for a 'backendMenu' method
        $menuResolver = function ($plugin) use (&$menu) {
            // get AppController classname for app or plugin
            $className = ($plugin) ? $plugin . '.' . 'App' : 'App';

            $class = \Cake\Core\App::className($className, 'Controller/Admin', 'Controller');
            if (!$class) {
                return;
            }

            // call static 'AppController::backendMenu()' method
            // and merge into $menu
            $callable = [$class, 'backendMenu'];
            if (is_callable($callable)) {
                $_menu = call_user_func($callable);
                //array_push($menu, (array) $_menu);
                $menu = array_merge($menu, (array) $_menu);
            }
        };

        // Resolve app menus
        $menuResolver(null);
        // Resolve hooked plugin menus
        array_walk($plugins, $menuResolver);
        // Append backend menu
        $menuResolver('Backend');

        return $menu;
    }
}