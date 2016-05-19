<?php

namespace Backend\Lib;

use Cake\Core\Plugin;

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
}
