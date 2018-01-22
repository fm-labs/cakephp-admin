<?php

namespace Backend\View\Helper\Layout;

use Backend\View\BackendView;
use Banana\Menu\Menu;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\View\Helper;

class SidebarHelper extends Helper
{
    protected $_defaultConfig = [
        'element' => 'Backend.Layout/admin/sidebar',
        'block' => 'sidebar'
    ];

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
        //if ($event->subject() instanceof BackendView) {

            $elements = [
                'menu' => ['element' => 'Backend.Layout/admin/sidebar/sidebar_menu', 'block' => 'sidebar_items'],
                //'search' => ['element' => 'Backend.Layout/admin/sidebar/sidebar_search', 'block' => 'sidebar_items'],
                //'user' => ['element' => 'Backend.Layout/admin/sidebar/sidebar_user', 'block' => 'sidebar_items'],
            ];

            // load sidebar menu
            $menu = new Menu();
            $this->_View->eventManager()->dispatch(new Event('Backend.Sidebar.get', $menu));
            $this->_View->set('backend.sidebar.menu', $menu);

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

            // inject sidebar into view block
            $event->subject()->Blocks->set($this->config('block'), $event->subject()->element($this->config('element')));
        //}
    }
}