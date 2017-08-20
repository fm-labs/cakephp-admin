<?php

namespace Backend\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

class CrudControllerListener implements EventListenerInterface
{
    public function implementedEvents()
    {
        return [
            'Backend.Controller.setupActions' => ['callable' => function(Event $event) {
                if (!isset($event->data['actions']['index'])) {
                    $event->data['actions']['index'] = 'Backend.Index';
                }
                if (!isset($event->data['actions']['add'])) {
                    $event->data['actions']['add'] = 'Backend.Add';
                }
                if (!isset($event->data['actions']['view'])) {
                    $event->data['actions']['view'] = 'Backend.View';
                }
                if (!isset($event->data['actions']['edit'])) {
                    $event->data['actions']['edit'] = 'Backend.Edit';
                }
                if (!isset($event->data['actions']['delete'])) {
                    $event->data['actions']['delete'] = 'Backend.Delete';
                }
            }]
        ];
    }
}