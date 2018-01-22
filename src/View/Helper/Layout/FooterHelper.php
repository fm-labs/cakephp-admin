<?php

namespace Backend\View\Helper\Layout;

use Cake\Event\Event;
use Cake\View\Helper;

class FooterHelper extends Helper
{
    protected $_defaultConfig = [
        'element' => 'Backend.Layout/admin/footer',
        'block' => 'footer'
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