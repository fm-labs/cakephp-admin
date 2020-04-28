<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Cake\Event\Event;
use Cake\View\Helper;

/**
 * Class DatepickerHelper
 *
 * @package Admin\View\Helper
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class DatepickerHelper extends Helper
{
    public $helpers = ['Html'];

    /**
     * @param \Cake\Event\Event $event The event object
     * @return void
     */
    public function beforeLayout(Event $event)
    {
        $this->Html->css('/admin/libs/pickadate/themes/classic', ['block' => true]);
        $this->Html->css('/admin/libs/pickadate/themes/classic.date', ['block' => true]);
        $this->Html->css('/admin/libs/pickadate/themes/classic.time', ['block' => true]);
        $this->Html->script('/admin/libs/pickadate/picker', ['block' => 'script']);
        $this->Html->script('/admin/libs/pickadate/picker.date', ['block' => 'script']);
        $this->Html->script('/admin/libs/pickadate/picker.time', ['block' => 'script']);
    }
}
