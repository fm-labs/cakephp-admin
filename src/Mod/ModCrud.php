<?php

namespace Backend\Mod;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

class ModCrud implements EventListenerInterface
{
    public function implementedEvents()
    {
        return [
            'Backend.Controller.setupActions' => ['callable' => function(Event $event) {

                if (isset($event->subject()->scaffold)) {

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
                }

            }]
        ];
    }
}