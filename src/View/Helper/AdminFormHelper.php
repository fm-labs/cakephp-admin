<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Bootstrap\View\Helper\FormHelper as BootstrapFormHelper;
use Cake\View\View;

class AdminFormHelper extends BootstrapFormHelper
{
    private $_fieldsetOptions = [];

    /**
     * {@inheritDoc}
     */
    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);

        $this->templater()->load('Admin.form_templates');

        $widgets = [
            //'checkbox' => ['Admin\View\Widget\CheckboxWidget'],
            // Chosen Select
            //'select' => ['Admin\View\Widget\ChosenSelectBoxWidget', '_view'],
            // Sumo Select
            //'select' => ['Admin\View\Widget\SumoSelectBoxWidget', '_view'],
            // Select2
            'select' => ['Admin\View\Widget\Select2Widget', '_view'],
            // TinyMCE Html Editor
            'htmleditor' => ['Admin\View\Widget\HtmlEditorWidget', '_view'],
            'htmltext' => ['Admin\View\Widget\HtmlTextWidget', '_view'],
            // Date and Time Pickers
            'datetime' => ['Admin\View\Widget\DatePickerWidget', 'text', '_view'], // override CakePHP built-in datetime control
            //'datepicker' => ['Admin\View\Widget\DatePickerWidget', 'text', '_view'],
            'timepicker' => ['Admin\View\Widget\TimePickerWidget'],
            // ACE Code Editor
            //'codeeditor' => ['Admin\View\Widget\CodeEditorWidget', '_view'],
            // Image select (experimental)
            //'imageselect' => ['Admin\View\Widget\ImageSelectWidget'],
            //'imagemodal' => ['Admin\View\Widget\ImageModalWidget'],
        ];
        foreach ($widgets as $type => $config) {
            $this->addWidget($type, $config);
        }
    }

    /**
     * Starts a fieldset block.
     *
     * @param string $legend Legend label
     * @param array $options Fieldset options
     * @return void
     */
    public function fieldsetStart($legend = null, $options = [])
    {
        if (is_array($legend)) {
            $options = $legend;
            $legend = null;
        } else {
            $options['legend'] = $legend;
        }

        if (isset($options['collapsed']) && $options['collapsed'] === true) {
            $options['fieldset']['class'] = isset($options['fieldset']) && isset($options['fieldset']['class'])
                ? $options['fieldset']['class'] . ' collapsed' : 'collapsed';
        }

        $this->_fieldsetOptions = $options;
        $this->_View->start('_fieldset');
    }

    /**
     * Ends a (previously started) fieldset block.
     *
     * @return string
     */
    public function fieldsetEnd()
    {
        $this->_View->end();

        $fields = $this->templater()->format('fieldsetBody', [
            'content' => $this->_View->fetch('_fieldset'),
            'attrs' => $this->templater()->formatAttributes([]),
        ]);

        return parent::fieldset($fields, $this->_fieldsetOptions);
    }

    /**
     * Creates a new form control.
     *
     * @param string $fieldName Field name
     * @param array $options Control options
     * @return string
     */
    public function input(string $fieldName, array $options = []): string
    {
        return parent::control($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    protected function _getInput($fieldName, $options)
    {
        //@TODO Dispatch Form.getInput event
        //debug("get input for $fieldName");
        //debug($options);

        //$options = $this->_getMagicInput($fieldName, $options);
        $context = $this->_getContext();

        //debug($fieldName);
        //debug($options);
        if (isset($options['type'])) {
            switch ($options['type']) {
                case 'checkbox':
                    //$options['nestedInput'] = false;
                    break;
                //case 'select':
                //    if (!$context->isRequired($fieldName)) {
                //        $options['empty'] = true;
                //    }
                //    break;
                //case 'imageselect':
                //case 'imagemodal':
                //    $this->Html->css('Admin.imagepicker/image-picker', ['block' => true]);
                //    $this->Html->script('Admin.imagepicker/image-picker.min', ['block' => 'script']);
                //    break;
            }
        }

        return parent::_getInput($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    protected function _parseOptions($fieldName, $options): array
    {
        //@TODO Dispatch Form.parseInput event
        //debug($fieldName);
        //debug($options);
        if (isset($options['type'])) {
            return parent::_parseOptions($fieldName, $options);
        }

        if ($fieldName == "created" || $fieldName == "modified" || $fieldName == "updated") {
            $options['type'] = 'hidden';
            $options['disabled'] = true;
        }

        if (preg_match('/_datetime$/', $fieldName)) {
            $options['type'] = 'datetime';
            //$options['type'] = ['datetimepicker'];
            //$options['type'] = 'datepicker';
        } elseif (preg_match('/_date$/', $fieldName)) {
            $options['type'] = 'datepicker';
        } elseif (preg_match('/_url$/', $fieldName)) {
            $options['type'] = 'url';
            $options['placeholder'] = 'https://';
        }
        //elseif (preg_match('/_time$/', $fieldName)) {
        //    $options['type'] = ['timepicker'];
        //}

        return parent::_parseOptions($fieldName, $options);
    }
}
