<?php

namespace Backend\View\Helper;

use Cake\View\Helper;
use Cake\View\Helper\FormHelper;
use Cake\View\Helper\HtmlHelper;

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

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        $this->Html->css('/backend/libs/chosen/chosen', ['block' => true]);
        $this->Html->script('/backend/libs/chosen/chosen.jquery', ['block' => 'script']);

        $this->Form->addWidget('chosen', ['Backend\View\Widget\ChosenSelectBoxWidget', '_view']);
    }
}
