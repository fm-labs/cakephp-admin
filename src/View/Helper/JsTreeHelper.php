<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Cake\Event\Event;
use Cake\View\Helper;

/**
 * Class JsTreeHelper
 * @package Sugar\View\Helper
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
        $this->Html->css('Admin.jstree/themes/admin/style.min', ['block' => true]);
        $this->Html->script('Admin.jstree/jstree.min', ['block' => 'script']);
    }
}
