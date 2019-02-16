<?php

namespace Backend\View\Helper\Layout;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\View\Helper;

class SidebarHelper extends Helper
{
    protected $_defaultConfig = [
        'element' => 'Backend.Layout/admin/sidebar',
        'block' => 'sidebar'
    ];

    /**
     * {@inheritDoc}
     */
    public function implementedEvents()
    {
        return [
            'View.beforeLayout' => ['callable' => 'beforeLayout']
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function beforeLayout(Event $event)
    {
        //debug("SidebarHelper::beforeLayout");
        $elements = [
            'menu' => ['cell' => 'Backend.SidebarMenu', 'block' => 'sidebar_items']
            //'menu' => ['element' => 'Backend.Layout/admin/sidebar/sidebar_menu', 'block' => 'sidebar_items'],
            //'search' => ['element' => 'Backend.Layout/admin/sidebar/sidebar_search', 'block' => 'sidebar_items'],
            //'user' => ['element' => 'Backend.Layout/admin/sidebar/sidebar_user', 'block' => 'sidebar_items'],
        ];

        // inject view elements
        foreach ($elements as $elementId => $element) {
            $elementHtml = "";
            try {
                if (isset($element['cell'])) {
                    $elementHtml = $event->subject()->cell($element['cell']);
                } else {
                    $elementHtml = $event->subject()->element($element['element']);
                }

            } catch (\Exception $ex) {
                $elementHtml = h($ex->getMessage());
            } finally {
                $event->subject()->append($element['block'], $elementHtml);
            }
        }

        // inject sidebar into view block
        $event->subject()->Blocks->set($this->config('block'), $event->subject()->element($this->config('element')));
    }
}
