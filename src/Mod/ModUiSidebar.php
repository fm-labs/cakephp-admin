<?php

namespace Backend\Mod;

use Backend\View\BackendView;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

class ModUiSidebar implements EventListenerInterface
{
    public function implementedEvents()
    {
        return ['View.beforeLayout' => ['callable' => 'beforeLayout']];
    }

    public function beforeLayout(Event $event)
    {
        if ($event->subject() instanceof BackendView) {

            $sidebar = [
                'backend_sidebar' => [
                    'menu' => ['element' => 'Backend.Layout/admin/sidebar/sidebar_menu'],
                ],
            ];

            foreach ($sidebar as $blockName => $elements) {
                foreach ($elements as $elementId => $element) {
                    $event->subject()->append($blockName, $event->subject()->element($element['element']));
                }
            }

        }

    }
}