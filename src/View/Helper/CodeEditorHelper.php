<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Cake\View\Helper;

/**
 * Class CodeEditorHelper
 * @package Admin\View\Helper
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\FormHelper $Form
 */
class CodeEditorHelper extends Helper
{
    public $helpers = ['Html', 'Form'];

    protected $_defaultConfig = [
        'scriptUrl' => '/admin/vendor/ace/1.4.1-noconflict/ace.js',
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

        $this->Form->addWidget('codeeditor', ['Admin\View\Widget\CodeEditorWidget', '_view']);
    }
}
