<?php

namespace Backend\View\Helper;

use Bootstrap\View\Helper\FormHelper as BootstrapFormHelper;
use Cake\View\View;

class BackendFormHelper extends BootstrapFormHelper
{
    private $_fieldsetOptions = [];

    public function __construct(View $View, array $config = [])
    {
        // custom checkbox templates
        // @TODO Move to CheckboxWidget
        $config['templates'] = [
            'checkboxFormGroup' => '<div class="checkbox">{{label}}</div>',
            'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}} /><span class="checkmark"></span>',
            //'inputContainerError' => '<div class="form-group has-error input-{{type}}{{required}}">{{error}}{{content}}</div>',
        ];
        $config['templatesHorizontal'] = [
            'checkbox' => '<div class="checkbox"><label><input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}} /><span class="checkmark"></span></label></div>',
        ];
        parent::__construct($View, $config);

        $this->templater()->load('Backend.form_templates');

        $widgets = [
            //'select' => ['Backend\View\Widget\ChosenSelectBoxWidget', '_view'],
            'select' => ['Backend\View\Widget\SumoSelectBoxWidget', '_view'],
            'htmleditor' => ['Backend\View\Widget\HtmlEditorWidget'],
            'htmltext' => ['Backend\View\Widget\HtmlTextWidget'],
            'datepicker' => ['Backend\View\Widget\DatePickerWidget', 'text'],
            //'datetime' => ['Backend\View\Widget\DatePickerWidget', 'text'],
            'timepicker' => ['Backend\View\Widget\TimePickerWidget'],
            'imageselect' => ['Backend\View\Widget\ImageSelectWidget'],
            'imagemodal' => ['Backend\View\Widget\ImageModalWidget'],
            'codeeditor' => ['Backend\View\Widget\CodeEditorWidget', '_view'],
        ];
        foreach ($widgets as $type => $config) {
            $this->addWidget($type, $config);
        }
    }

    public function fieldsetStart($legend = null, $options = [])
    {
        if (is_array($legend)) {
            $options = $legend;
            $legend = null;
        } else {
            $options['legend'] = $legend;
        }

        if (isset($options['collapsed']) && $options['collapsed'] === true) {
            $options['fieldset']['class'] = (isset($options['fieldset']) && isset($options['fieldset']['class']))
                ? $options['fieldset']['class'] . ' collapsed' : 'collapsed';
        }

        $this->_fieldsetOptions = $options;
        $this->_View->Blocks->start('_fieldset');
    }

    public function fieldsetEnd()
    {
        $this->_View->Blocks->end();

        $fields = $this->templater()->format('fieldsetBody', [
            'content' => $this->_View->Blocks->get('_fieldset'),
            'attrs' => $this->templater()->formatAttributes([])
        ]);

        return parent::fieldset($fields, $this->_fieldsetOptions);
    }

    /**
     * Override FormHelper::_getInput() to enable lazy loading of helpers for certain input types
     * @TODO Move helper injection to widget class
     *
     *
     * @param string $fieldName
     * @param array $options
     * @return string
     */
    protected function _getInput($fieldName, $options)
    {
        //@TODO Dispatch Form.getInput event

        //$options = $this->_getMagicInput($fieldName, $options);

        $context = $this->_getContext();

        //debug($fieldName);
        //debug($options);
        if (isset($options['type'])) {
            switch ($options['type']) {
                case 'select':
                    if (!$context->isRequired($fieldName)) {
                        $options['empty'] = true;
                    }
                    //$this->_View->loadHelper('Backend.Chosen');
                    break;

                case 'datepicker':
                //case 'datetime':
                    $this->_View->loadHelper('Backend.Datepicker');
                    break;

                case 'htmleditor':
                case 'htmltext':
                    $this->_View->loadHelper('Backend.HtmlEditor');
                    break;

                case 'imageselect':
                case 'imagemodal':
                    $this->Html->css('Backend.imagepicker/image-picker', ['block' => true]);
                    $this->Html->script('Backend.imagepicker/image-picker.min', ['block' => 'script']);
                    break;
            }
        }

        return parent::_getInput($fieldName, $options);
    }

    /**
     * Magically get input options
     */
    protected function _parseOptions($fieldName, $options)
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
        }
        elseif (preg_match('/_date$/', $fieldName)) {
            $options['type'] = 'datepicker';
        }
        //elseif (preg_match('/_time$/', $fieldName)) {
        //    $options['type'] = ['timepicker'];
        //}
        elseif (preg_match('/_url$/', $fieldName)) {
            $options['type'] = 'url';
            $options['placeholder'] = 'https://';
        }

        return parent::_parseOptions($fieldName, $options);
    }
}
