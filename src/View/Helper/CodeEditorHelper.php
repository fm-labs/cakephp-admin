<?php
declare(strict_types=1);

namespace Backend\View\Helper;

use Cake\View\Helper;

/**
 * Class CodeEditorHelper
 * @package Backend\View\Helper
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\FormHelper $Form
 */
class CodeEditorHelper extends Helper
{
    public $helpers = ['Html', 'Form'];

    protected $_defaultConfig = [
        'scriptUrl' => '/backend/vendor/ace/1.4.1-noconflict/ace.js',
        'scriptBlock' => true,
    ];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        $this->Html->script($this->getConfig('scriptUrl'), [
            'block' => $this->getConfig('scriptBlock'),
        ]);

        $this->Form->addWidget('codeeditor', ['Backend\View\Widget\CodeEditorWidget', '_view']);
    }
}
