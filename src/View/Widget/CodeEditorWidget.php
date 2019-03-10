<?php
namespace Backend\View\Widget;

use Cake\Core\Configure;
use Cake\View\Form\ContextInterface;
use Cake\View\View;
use Cake\View\Widget\TextareaWidget;

/**
 * Class CodeEditorWidget
 *
 * Code editor widget using Ace - The high performance code editor for the web
 * @link https://ace.c9.io/
 *
 * @package Backend\View\Widget
 */
class CodeEditorWidget extends TextareaWidget
{
    /**
     * Default ACE editor config
     * @var array
     */
    public static $defaultConfig = [
        'mode' => null,
        'theme' => 'twilight',

        'minLines' => 10,
        'maxLines' => 30,
        'scrollPastEnd' => true,
        'vScrollBarAlwaysVisible' => true
    ];

    /**
     * Reference to the view instance
     *
     * @var View
     */
    protected $_View;

    /**
     * @var array
     */
    protected $_config;

    /**
     * @param \Cake\View\StringTemplate $templates The templater instance
     * @param View $view The view instance
     */
    public function __construct($templates, View $view)
    {
        parent::__construct($templates);
        $this->_View = $view;
        $this->_View->loadHelper('Backend.Backbone');
        $this->_View->loadHelper('Backend.CodeEditor');

        $this->_templates->add([
            'codeeditor_editor' => '<div{{attrs}}>{{value}}</div>'
        ]);

        $this->_config = (array)Configure::read('Backend.CodeEditor.Ace');
    }

    /**
     * Render a text area element which will be converted to a tinymce htmleditor.
     *
     * Data supports the following keys
     *
     * @param array $data The data to build a textarea with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string HTML elements.
     */
    public function render(array $data, ContextInterface $context)
    {
        $data += [
            'val' => '',
            'name' => '',
            'escape' => true,
            'class' => '',
            'id' => '',
            'editor' => [],
        ];

        // Textarea
        $inputData = $data;
        $inputData['id'] = ($inputData['id']) ?: uniqid('codeeditor');
        $inputData['class'] = 'codeeditor-input';
        $inputData['style'] = 'display:none;';
        unset($inputData['editor']);
        unset($inputData['type']);
        $input = parent::render($inputData, $context);

        // Editor
        $editor = array_merge(static::$defaultConfig, $this->_config, $data['editor']);
        // auto-prefix 'mode' and 'theme' editor options
        $editor['mode'] = ($editor['mode'] && !preg_match('/\//', $editor['mode'])) ? 'ace/mode/' . $editor['mode'] : $editor['mode'];
        $editor['theme'] = ($editor['theme'] && !preg_match('/\//', $editor['theme'])) ? 'ace/theme/' . $editor['theme'] : $editor['theme'];

        // build editor html
        $defaultClass = 'codeeditor';
        $data['id'] = $inputData['id'] . '-editor';
        $data['class'] = ($data['class']) ? $data['class'] . ' ' . $defaultClass : $defaultClass;
        $editorHtml = $this->_templates->format('codeeditor_editor', [
            'name' => $data['name'],
            'value' => $data['escape'] ? h($data['val']) : $data['val'],
            'attrs' => $this->_templates->formatAttributes(
                $data,
                ['name', 'val', 'type', 'editor']
            )
        ]);

        // build editor javascript and inject into view
        $scriptTemplate = <<<SCRIPT
        (function(inputTarget, editorId, config) {
            var editor = ace.edit(editorId, config);
            editor.session.on('change', function() {
                $('#' + inputTarget).text(editor.getValue());
            });
            //editor.setValue($('#' + inputTarget).text(), -1);
        })("%s", "%s", %s)
SCRIPT;
        $script = sprintf($scriptTemplate, $inputData['id'], $data['id'], json_encode($editor));
        $this->_View->Html->scriptBlock($script, ['block' => true]);

        return $input . $editorHtml;
    }
}
