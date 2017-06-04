<?php

namespace Backend\Event;

use Cake\Event\Event;
use Cake\Routing\RouteBuilder;

/**
 * Class SetupEvent
 *
 * @package Backend\Event
 */
class RouteBuilderEvent extends Event
{
    /**
     * @return RouteBuilder
     */
    public function subject()
    {
        return parent::subject();
    }
}