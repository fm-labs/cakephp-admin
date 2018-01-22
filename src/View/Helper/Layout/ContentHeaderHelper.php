<?php

namespace Backend\View\Helper\Layout;

use Cake\Event\Event;
use Cake\View\Helper;

class ContentHeaderHelper extends Helper
{
    protected $_defaultConfig = [
        'element' => 'Backend.Layout/admin/content_header',
        'block' => 'content_header'
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