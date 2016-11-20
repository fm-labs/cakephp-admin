<?php

namespace Backend\Lib;


use Cake\Core\Configure;
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
            $configKey = $configPrefix . 'Menu.' . $scope;

            $_menu = [];
            if (Configure::check($configKey)) {
                $_menu = Configure::read($configKey);
            }

            elseif (file_exists($configFile)) {
                $config = include $configFile;
                $config = Hash::expand($config);

                if (Hash::check($config, $configKey)) {
                    //$menuKey = ($plugin) ? $plugin : 'App';
                    //$menu = array_merge($menu, [ $menuKey => Hash::get($config, $key)]);
                    $_menu = Hash::get($config, $configKey);

                }
            }

            foreach ((array) $_menu as $menuItemKey => $menuItem) {
                if (is_numeric($menuItemKey)) {
                    $menu[] = $menuItem;
                } else {
                    $menu[$menuItemKey] = $menuItem;
                }
            }
            return;
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