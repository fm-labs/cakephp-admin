<?php

namespace Backend\View\Helper;


use Cake\Event\Event;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;

/**
 * Class JsTreeHelper
 * @package Backend\View\Helper
 * @property HtmlHelper $Html
 */
class JsTreeHelper extends Helper
{
    public $helpers = ['Html'];

    public function beforeLayout(Event $event)
    {
        $this->Html->css('/backend/css/jstree/themes/backend/style', ['block' => true]);
        $this->Html->script('/backend/libs/jstree/jstree.min', ['block' => 'scriptBottom']);
    }
}