<?php

namespace Backend\Event;

use Cake\Event\Event;
use Cake\Event\EventManager;

/**
 * LocalEventManager
 *
 * Behaves like Cake's built-in event manager, but prevent bubble up events to the the global event manager
 */
class LocalEventManager extends EventManager
{
    /**
     * @var EventManager
     */
    static protected $_generalLocalManager = null;

    /**
     * Override instance call
     * Simply generate a new global event manager on every invocation
     */
    public static function instance($manager = null)
    {
        static::$_generalLocalManager = new EventManager();
        static::$_generalLocalManager->_isGlobal = true;

        return static::$_generalLocalManager;
    }

    public function dispatch($event) {
        //debug("dispatching event " . $event->name());
        return parent::dispatch($event);
    }

    public function __debugInfo()
    {
        $properties = parent::__debugInfo();
        $properties['_generalLocalManager'] = '(object) EventManager';

        return $properties;
    }
}