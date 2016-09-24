<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 9/16/16
 * Time: 9:36 PM
 */

namespace Backend\View\Helper;


use Cake\Event\Event;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;

/**
 * Class TinyMceHelper
 * @package Backend\View\Helper
 *
 * @property HtmlHelper $Html
 */
class TinyMceHelper extends Helper
{
    public $helpers = ['Html'];

    public function beforeRender(Event $event)
    {
        //$this->Html->script('_tinymce', ['block' => 'scriptBottom']);
    }
}