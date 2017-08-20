<?php

namespace Backend\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

class PublishableControllerListener implements EventListenerInterface
{
    public function implementedEvents()
    {
        return [
            'Backend.Controller.setupActions' => ['callable' => function(Event $event) {

                $modelClass = $event->subject()->modelClass;
                if ($modelClass) {
                    $Model = $event->subject()->loadModel($modelClass);
                    if ($Model->behaviors()->has('Publishable')) {
                        $event->data['actions']['publish'] = 'Backend.Publish';
                        $event->data['actions']['unpublish'] = 'Backend.Unpublish';
                    }
                }


            }]
        ];
    }
}