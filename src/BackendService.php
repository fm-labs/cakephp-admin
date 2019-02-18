<?php

namespace Backend;

use Cake\Event\EventListenerInterface;

abstract class BackendService implements EventListenerInterface
{
    /**
     * {@inheritDoc}
     */
    public function implementedEvents()
    {
        return [];
    }
}
