<?php

namespace Backend\Lib;


use Cake\Core\Configure;
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

            $_menu = Backend::getMenu($plugin);
            if ($_menu) {
                $menu[] = $_menu;
            }
        };

        // Resolve app menus
        $menuResolver(null);
        // Resolve hooked plugin menus
        array_walk($plugins, $menuResolver);

        return $menu;
    }
}