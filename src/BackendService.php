<?php

namespace Backend;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

class BackendService implements EventListenerInterface
{
    public function implementedEvents()
    {
        return [];
    }

    public function initialize()
    {

    }
}