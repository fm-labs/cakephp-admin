<?php

namespace Backend\View\Helper\Layout;

use Backend\View\BackendView;
use Banana\Menu\Menu;
use Banana\Menu\MenuItem;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\View\Helper;

class HeaderHelper extends Helper
{
    protected $_defaultConfig = [
        'element' => 'Backend.Layout/admin/header',
        'block' => 'header'
    ];

    public function implementedEvents()
    {
        return [
            'View.beforeLayout' => ['callable' => 'beforeLayout']
        ];
    }

    public function beforeLayout(Event $event)
    {
        //if ($event->subject() instanceof BackendView) {

            $elements = [
                'menu' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_menu', 'block' => 'header_navbar_menu'],
                //'search' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_search', 'block' => 'header_navbar_items'],
                //'messages' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_messages', 'block' => 'header_navbar_items'],
                //'notifications' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_notifications', 'block' => 'header_navbar_items'],
                //'tasks' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_tasks', 'block' => 'header_navbar_items'],
                'sysmenu' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_sysmenu', 'block' => 'header_navbar_items'],
                'user' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_user', 'block' => 'header_navbar_items'],
            ];


            // load backend menu
            $menu = new Menu();
            $this->_View->eventManager()->dispatch(new Event('Backend.Menu.build', $menu));
            $event->subject()->set('backend.navbar.menu', $menu);


            // load backend sysmenu
            $menu = new Menu();
            $this->_View->eventManager()->dispatch(new Event('Backend.SysMenu.build', $menu));

            $sysmenu = new Menu();
            $item = new MenuItem([
                'title' => 'System',
                'url' => ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                'data-icon' => 'gears',
                'children' => $menu
            ]);
            $sysmenu->addItem($item);
            $event->subject()->set('backend.navbar.sysmenu', $sysmenu);

            // inject view elements
            foreach ($elements as $elementId => $element) {
                $elementHtml = "";
                try {
                    $elementHtml = $event->subject()->element($element['element']);
                } catch (\Exception $ex) {
                    $elementHtml = h($ex->getMessage());
                } finally {
                    $event->subject()->append($element['block'], $elementHtml);
                }
            }

            $event->subject()->Blocks->set($this->config('block'), $event->subject()->element($this->config('element')));
        //}

    }
}