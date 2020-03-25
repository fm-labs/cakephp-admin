<?php
declare(strict_types=1);

namespace Backend\View\Helper;

use Cake\View\Helper;

/**
 * Class DateRangePickerHelper
 * @package Backend\View\Helper
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\FormHelper $Form
 */
class DateRangePickerHelper extends Helper
{
    public $helpers = ['Html', 'Form'];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        $this->Html->css('/backend/libs/daterangepicker/daterangepicker.css', ['block' => true]);
        $this->Html->script('/backend/libs/daterangepicker/daterangepicker.js', ['block' => true]);

        $this->Form->addWidget('daterange', ['Backend\View\Widget\DateRangePickerWidget', '_view']);
    }
}
