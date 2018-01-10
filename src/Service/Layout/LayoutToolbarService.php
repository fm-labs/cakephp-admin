<?php

namespace Backend\Service\Layout;

use Backend\BackendService;
use Backend\View\BackendView;
use Cake\Core\Configure;
use Cake\Event\Event;

class LayoutToolbarService extends BackendService
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

            $elementPath = 'Backend.Layout/admin/toolbar';
            $event->subject()->Blocks->set('toolbar', $event->subject()->element($elementPath));

        }
    }
}