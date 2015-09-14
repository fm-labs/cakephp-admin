<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 9/10/15
 * Time: 9:41 PM
 */

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
