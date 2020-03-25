<?php
declare(strict_types=1);

namespace Backend;

use Cake\Event\EventListenerInterface;

abstract class BackendService implements EventListenerInterface
{
    /**
     * {@inheritDoc}
     */
    public function implementedEvents(): array
    {
        return [];
    }
}
