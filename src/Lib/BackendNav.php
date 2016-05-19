<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 2/13/16
 * Time: 4:51 PM
 */

namespace Backend\Lib;


use Cake\Core\Plugin;
use Cake\Utility\Hash;

class BackendNav
{
    static public function getMenu()
    {
        $menu = [];
        $plugins = Backend::plugins();

        //check each plugin's AppController for a 'backendMenu' method
        $menuResolver = function ($plugin) use (&$menu) {

            $configPath = ($plugin) ? Plugin::configPath($plugin) : CONFIG;
            $configFile = $configPath . 'backend.php';
            $configPrefix = ($plugin) ? 'Backend.Plugin.' . $plugin . '.' : 'Backend.';

            if (file_exists($configFile)) {
                $config = include $configFile;
                $config = Hash::expand($config);

                if (Hash::check($config, $configPrefix . 'Menu')) {
                    $menuKey = ($plugin) ? $plugin : 'app';
                    $menu = array_merge($menu, [ $menuKey => Hash::get($config, $configPrefix . 'Menu')]);
                    return;
                }
            }

            /**
             * DEPRECATED BACKEND MENU LOADER
             * Loads from static AppController::backendMenu() method.
             * The config method is prefered.
             */
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
        //$menuResolver('Backend');

        return $menu;
    }
}