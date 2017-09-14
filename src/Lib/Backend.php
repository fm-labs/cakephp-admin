<?php

namespace Backend\Lib;

use Banana\Exception\ClassNotFoundException;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\EventListenerInterface;

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
    protected static $_version = '0.0.0';

    protected static $_listeners = [];

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

    public static function registerListener($type, $listenerClass)
    {
        if (!isset(static::$_listeners[$type])) {
            static::$_listeners[$type] = [];
        }

        if (!class_exists($listenerClass)) {
            throw new ClassNotFoundException($listenerClass);
        }

        static::$_listeners[$type][] = $listenerClass;
    }

    public static function getListeners($type)
    {
        if (!isset(static::$_listeners[$type])) {
            return [];
        }

        return static::$_listeners[$type];
    }
}
