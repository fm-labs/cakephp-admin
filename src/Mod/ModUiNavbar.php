<?php

namespace Backend\Mod;

use Backend\View\BackendView;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Utility\Inflector;

class ModUiNavbar implements EventListenerInterface
{
    public function implementedEvents()
    {
        return ['View.beforeLayout' => ['callable' => 'beforeLayout']];
    }

    public function beforeLayout(Event $event)
    {
        if ($event->subject() instanceof BackendView) {

            $navbar = [
                'backend_navbar_left' => [

                ],
                'backend_navbar_right' => [
                    'search' => ['element' => 'Backend.Layout/admin/navbar/navbar_search'],
                    'messages' => ['element' => 'Backend.Layout/admin/navbar/navbar_messages'],
                    'notifications' => ['element' => 'Backend.Layout/admin/navbar/navbar_notifications'],
                    'tasks' => ['element' => 'Backend.Layout/admin/navbar/navbar_tasks'],
                    'user' => ['element' => 'Backend.Layout/admin/navbar/navbar_user'],
                ],
            ];

            foreach ($navbar as $blockName => $elements) {
                foreach ($elements as $elementId => $element) {
                    $event->subject()->append($blockName, $event->subject()->element($element['element']));
                }
            }

        }

    }
}