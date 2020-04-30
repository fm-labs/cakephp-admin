<?php
declare(strict_types=1);

namespace Admin\Service;

use Cake\Event\EventListenerInterface;

abstract class AdminService implements EventListenerInterface
{
    /**
     * {@inheritDoc}
     */
    public function implementedEvents(): array
    {
        return [];
    }
}
