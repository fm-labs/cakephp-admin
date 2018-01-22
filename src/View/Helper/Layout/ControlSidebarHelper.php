<?php

namespace Backend\View\Helper\Layout;

use Cake\Error\Debugger;
use Cake\Event\Event;
use Cake\View\Helper;

class ControlSidebarHelper extends Helper
{
    protected $_defaultConfig = [
        'element' => 'Backend.Layout/admin/control_sidebar',
        'block' => 'control_sidebar'
    ];

    public function implementedEvents()
    {
        return [
            'View.beforeLayout' => ['callable' => 'beforeLayout']
        ];
    }

    public function beforeLayout(Event $event)
    {
        $event->subject()->Blocks->set($this->config('block'), $event->subject()->element($this->config('element')));
    }
}