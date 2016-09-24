<?php

namespace Backend\View\Helper;


use Cake\Event\Event;
use Cake\View\Helper;
use Cake\View\Helper\FormHelper;
use Cake\View\Helper\HtmlHelper;
use Cake\View\View;

/**
 * Class ChosenHelper
 * @package Backend\View\Helper
 *
 * @property HtmlHelper $Html
 * @property FormHelper $Form
 */
class ChosenHelper extends Helper
{
    public $helpers = ['Html', 'Form'];


    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);

        $this->Form->addWidget('chosen', ['Backend\View\Widget\ChosenSelectBoxWidget']);
        $this->Form->addWidget('select', ['Backend\View\Widget\ChosenSelectBoxWidget']);
    }

        public function beforeLayout(Event $event)
    {
        $this->Html->css('Backend.chosen/chosen.min', ['block' => true]);
        $this->Html->script('_chosen', ['block' => 'scriptBottom']);
    }
}