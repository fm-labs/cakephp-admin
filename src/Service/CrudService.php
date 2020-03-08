<?php

namespace Backend\Service;

use Backend\BackendService;
use Cake\Event\Event;

class CrudService extends BackendService
{
    /**
     * {@inheritDoc}
     */
    public function implementedEvents()
    {
        return [
            'Backend.Controller.setupActions' => ['callable' => function (Event $event) {

                if (isset($event->getSubject()->scaffold)) {
                    if (!isset($event->getData('actions')['index'])) {
                        $event->getData('actions')['index'] = 'Backend.Index';
                    }
                    if (!isset($event->getData('actions')['add'])) {
                        $event->getData('actions')['add'] = 'Backend.Add';
                    }
                    if (!isset($event->getData('actions')['view'])) {
                        $event->getData('actions')['view'] = 'Backend.View';
                    }
                    if (!isset($event->getData('actions')['edit'])) {
                        $event->getData('actions')['edit'] = 'Backend.Edit';
                    }
                    if (!isset($event->getData('actions')['delete'])) {
                        $event->getData('actions')['delete'] = 'Backend.Delete';
                    }
                }
            }],
        ];
    }
}
