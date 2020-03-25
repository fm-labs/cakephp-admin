<?php
declare(strict_types=1);

namespace Backend\View\Helper;

use Cake\Event\Event;
use Cake\View\Helper;

/**
 * Class DatepickerHelper
 *
 * @package Backend\View\Helper
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
        $this->Html->css('/backend/libs/pickadate/themes/classic', ['block' => true]);
        $this->Html->css('/backend/libs/pickadate/themes/classic.date', ['block' => true]);
        $this->Html->css('/backend/libs/pickadate/themes/classic.time', ['block' => true]);
        $this->Html->script('/backend/libs/pickadate/picker', ['block' => 'script']);
        $this->Html->script('/backend/libs/pickadate/picker.date', ['block' => 'script']);
        $this->Html->script('/backend/libs/pickadate/picker.time', ['block' => 'script']);
    }
}
