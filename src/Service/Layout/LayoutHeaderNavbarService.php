<?php

namespace Backend\Service\Layout;

use Backend\BackendService;
use Backend\View\BackendView;
use Banana\Menu\Menu;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventManager;

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


            // inject sidebar menu
            $menu = new Menu();
            $_event = new Event('Backend.Menu.get', $menu);
            //$this->eventManager()->dispatch($_event);
            EventManager::instance()->dispatch($_event);
            $event->subject()->set('backend.navbar.menu', $menu);
            //debug($menu);
            $navbar = [
                'backend_navbar_left' => [
                    'menu' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_content_menu'],
                ],
                'backend_navbar_right' => [
                    //'search' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_search'],
                    //'messages' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_messages'],
                    //'notifications' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_notifications'],
                    //'tasks' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_tasks'],
                    'user' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_user'],
                ],
            ];

            foreach ($navbar as $blockName => $elements) {
                foreach ($elements as $elementId => $element) {
                    $elementHtml = "";
                    try {
                        $elementHtml = $event->subject()->element($element['element']);
                    } catch (\Exception $ex) {
                        debug($ex);
                        $elementHtml = h($ex->getMessage());
                    } finally {
                        $event->subject()->append($blockName, $elementHtml);
                    }
                }
            }

        }

    }
}