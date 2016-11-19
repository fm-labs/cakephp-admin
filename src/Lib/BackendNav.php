<?php

namespace Backend\Lib;


use Cake\Core\Plugin;
use Cake\Utility\Hash;

class BackendNav
{
    static public function getMenu($scope = 'app')
    {
        $menu = [];
        $plugins = Backend::plugins();

        //check each plugin's AppController for a 'backendMenu' method
        $menuResolver = function ($plugin) use (&$menu, $scope) {

            $configPath = ($plugin) ? Plugin::configPath($plugin) : CONFIG;
            $configFile = $configPath . 'backend.php';
            $configPrefix = ($plugin) ? 'Backend.Plugin.' . $plugin . '.' : 'Backend.';

            if (file_exists($configFile)) {
                $config = include $configFile;
                $config = Hash::expand($config);

                $key = $configPrefix . 'Menu.' . $scope;
                if (Hash::check($config, $key)) {
                    //$menuKey = ($plugin) ? $plugin : 'App';
                    //$menu = array_merge($menu, [ $menuKey => Hash::get($config, $key)]);
                    foreach (Hash::get($config, $key) as $menuItemKey => $menuItem) {
                        if (is_numeric($menuItemKey)) {
                            $menu[] = $menuItem;
                        } else {
                            $menu[$menuItemKey] = $menuItem;
                        }
                    }
                    return;
                }
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