<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Cake\Event\Event;
use Cake\View\Helper;

/**
 * Class JsTreeHelper
 * @package Admin\View\Helper
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
        $this->Html->css('/admin/css/jstree/themes/admin/style', ['block' => true]);
        $this->Html->script('/admin/libs/jstree/jstree.min', ['block' => 'script']);
    }
}
