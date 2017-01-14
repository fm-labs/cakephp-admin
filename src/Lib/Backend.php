<?php

namespace Backend\Lib;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Utility\Hash;

class Backend
{
    protected static $_version;

    protected static $_plugins = [];

    /**
     * @return string Backend version number
     */
    public static function version()
    {
        if (!isset(static::$_version)) {
            static::$_version = @file_get_contents(Plugin::path('Backend') . DS . 'VERSION.txt');
        }
        return static::$_version;
    }

    /**
     * @return array List of hooked plugins
     */
    public static function plugins()
    {
        //if (Plugin::loaded('Banana')) {
        //    return \Banana\Lib\BananaPlugin::installed();
        //}
        return Plugin::loaded();
    }

    /**
     * Hook plugin by name
     *
     * @param string $pluginName Plugin name
     * @param array $config
     */
    public static function hookPlugin($pluginName)
    {
        static::$_plugins[$pluginName] = [];
    }

    public static function getMenu($pluginName = null)
    {
        $menu = [];

        //check each plugin's AppController for a 'backendMenu' method
        $menuResolver = function ($plugin) use (&$menu) {

            $configPath = ($plugin) ? Plugin::configPath($plugin) : CONFIG;
            $configFile = $configPath . 'backend.php';

            // App:: App.Backend.Menu
            // Plugin: PLUGIN.Backend.Menu
            // Backend: Backend.Menu
            $configKey = 'Backend.Menu';
            if (!$plugin) {
                $configKey = 'App.' . $configKey;
            } elseif ($plugin && $plugin != 'Backend') {
                $configKey = $plugin . '.' . $configKey;
            }
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

        $menuResolver($pluginName);
        return $menu;
    }
}
