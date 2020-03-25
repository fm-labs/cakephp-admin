<?php
declare(strict_types=1);

namespace Backend\View\Helper;

use Cake\Event\Event;
use Cake\View\Helper;

/**
 * Class JsTreeHelper
 * @package Backend\View\Helper
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class JsTreeHelper extends Helper
{
    public $helpers = ['Html'];

    /**
     * @param \Cake\Event\Event $event The event object
     * @return void
     */
    public function beforeLayout(Event $event)
    {
        $this->Html->css('/backend/css/jstree/themes/backend/style', ['block' => true]);
        $this->Html->script('/backend/libs/jstree/jstree.min', ['block' => 'script']);
    }
}
