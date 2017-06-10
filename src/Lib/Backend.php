<?php

namespace Backend\Lib;

use Cake\Core\Configure;
use Cake\Core\Plugin;

/**
 * Class Backend
 *
 * @package Backend\Lib
 */
class Backend
{
    /**
     * @var string
     */
    protected static $_version;

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
}
