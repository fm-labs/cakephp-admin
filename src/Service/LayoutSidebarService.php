<?php

namespace Backend\Service;

use Backend\BackendService;
use Backend\View\BackendView;
use Banana\Menu\Menu;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;

class LayoutSidebarService extends BackendService implements EventDispatcherInterface
{
    use EventDispatcherTrait;

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

            // inject sidebar menu
            $menu = new Menu();
            $_event = new Event('Backend.Menu.get', $menu);
            $this->eventManager()->dispatch($_event);
            $event->subject()->set('backend.sidebar.menu', $menu);

            // inject sidebar elements
            $sidebar = [
                'backend_sidebar' => [
                    'menu' => ['element' => 'Backend.Layout/admin/sidebar/sidebar_menu'],
                    //'search' => ['element' => 'Backend.Layout/admin/sidebar/sidebar_search'],
                    //'user' => ['element' => 'Backend.Layout/admin/sidebar/sidebar_user'],
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