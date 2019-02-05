<?php

namespace Backend\View\Helper;

use Cake\Event\Event;
use Cake\View\Helper;
use Cake\View\Helper\FormHelper;
use Cake\View\Helper\HtmlHelper;
use Cake\View\View;

/**
 * Class SwitchControlHelper
 * @package Backend\View\Helper
 *
 * @property HtmlHelper $Html
 * @property FormHelper $Form
 */
class SwitchControlHelper extends Helper
{
    public $helpers = ['Html', 'Form'];

    /**
     * {@inheritDoc}
     */
    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);

        $this->Html->css('Backend./vendor/bootstrap-switch/css/bootstrap3/bootstrap-switch.css', ['block' => true]);
        $this->Html->script('Backend./vendor/bootstrap-switch/js/bootstrap-switch.js', ['block' => true]);
        //$this->Form->addWidget('chosen', ['Backend\View\Widget\SwitchControlSelectBoxWidget']);
        $this->Form->addWidget('switch', ['Backend\View\Widget\SwitchControlWidget', '_view']);
    }
}
