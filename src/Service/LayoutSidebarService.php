<?php

namespace Backend\Service;

use Backend\BackendService;
use Backend\View\BackendView;
use Cake\Core\Configure;
use Cake\Event\Event;

class LayoutSidebarService extends BackendService
{
    public function implementedEvents()
    {
        return [
            'View.beforeRender' => ['callable' => 'beforeRender'],
            'View.beforeLayout' => ['callable' => 'beforeLayout']
        ];
    }

    public function beforeRender(Event $event) {}

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