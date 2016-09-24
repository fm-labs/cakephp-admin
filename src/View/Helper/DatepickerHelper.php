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
class DatepickerHelper extends Helper
{
    public $helpers = ['Html'];

    public function beforeLayout(Event $event)
    {
        $this->Html->css('Backend.pickadate/themes/classic', ['block' => true]);
        $this->Html->css('Backend.pickadate/themes/classic.date', ['block' => true]);
        $this->Html->css('Backend.pickadate/themes/classic.time', ['block' => true]);
        $this->Html->script('_pickadate', ['block' => 'scriptBottom']);
    }
}