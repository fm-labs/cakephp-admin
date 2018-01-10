<?php

namespace Backend\Service\Layout;

use Backend\BackendService;
use Backend\View\BackendView;
use Cake\Core\Configure;
use Cake\Event\Event;

class LayoutHeaderNavbarService extends BackendService
{
    public function implementedEvents()
    {
        return [
            'View.beforeRender' => ['callable' => 'beforeRender', 'priority' => 9],
            'View.beforeLayout' => ['callable' => 'beforeLayout', 'priority' => 9]
        ];
    }

    public function beforeRender(Event $event) {}

    public function beforeLayout(Event $event)
    {
        if ($event->subject() instanceof BackendView) {

            $navbar = [
                'backend_navbar_left' => [

                ],
                'backend_navbar_right' => [
                    'search' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_search'],
                    'messages' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_messages'],
                    'notifications' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_notifications'],
                    'tasks' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_tasks'],
                    'user' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_user'],
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