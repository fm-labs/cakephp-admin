<?php
declare(strict_types=1);

namespace Backend\View\Helper;

use Cake\View\Helper;

/**
 * Class SwitchControlHelper
 * @package Backend\View\Helper
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\FormHelper $Form
 */
class SwitchControlHelper extends Helper
{
    public $helpers = ['Html', 'Form'];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        $this->Html->css('Backend./vendor/bootstrap-switch/css/bootstrap3/bootstrap-switch.css', ['block' => true]);
        $this->Html->script('Backend./vendor/bootstrap-switch/js/bootstrap-switch.js', ['block' => true]);
        //$this->Form->addWidget('chosen', ['Backend\View\Widget\SwitchControlSelectBoxWidget']);
        $this->Form->addWidget('switch', ['Backend\View\Widget\SwitchControlWidget', '_view']);
    }
}
