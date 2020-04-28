<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Cake\View\Helper;

/**
 * Class ChosenHelper
 * @package Admin\View\Helper
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\FormHelper $Form
 */
class ChosenHelper extends Helper
{
    public $helpers = ['Html', 'Form'];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        $this->Html->css('/admin/libs/chosen/chosen', ['block' => true]);
        $this->Html->script('/admin/libs/chosen/chosen.jquery', ['block' => 'script']);

        $this->Form->addWidget('chosen', ['Admin\View\Widget\ChosenSelectBoxWidget', '_view']);
    }
}
