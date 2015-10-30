<?php

namespace Backend\Lib;

use Cake\Core\Plugin;

class Backend
{
    public static $version;

    public static function version()
    {
        if (!isset(static::$version)) {
            static::$version = @file_get_contents(Plugin::path('Backend') . DS . 'VERSION.txt');
        }
        return static::$version;
    }
}
