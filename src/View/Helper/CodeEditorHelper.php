<?php

namespace Backend\View\Helper;

use Cake\View\Helper;
use Cake\View\Helper\FormHelper;
use Cake\View\Helper\HtmlHelper;

/**
 * Class CodeEditorHelper
 * @package Backend\View\Helper
 *
 * @property HtmlHelper $Html
 * @property FormHelper $Form
 */
class CodeEditorHelper extends Helper
{
    public $helpers = ['Html', 'Form'];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config)
    {
        $this->Html->script('/backend/vendor/ace/1.4.1-noconflict/ace.js', ['block' => true]);

        $this->Form->addWidget('codeeditor', ['Backend\View\Widget\CodeEditorWidget', '_view']);
    }
}
