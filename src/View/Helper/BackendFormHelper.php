<?php

namespace Backend\View\Helper;

use Bootstrap\View\Helper\FormHelper as BootstrapFormHelper;
use Cake\View\View;

class BackendFormHelper extends BootstrapFormHelper
{
    private $_fieldsetOptions = [];

    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);

        $this->templater()->load('Backend.form_templates');

        $widgets = [
            //'select' => ['Backend\View\Widget\ChosenSelectBoxWidget'],
            'htmleditor' => ['Backend\View\Widget\HtmlEditorWidget'],
            'htmltext' => ['Backend\View\Widget\HtmlTextWidget'],
            'datepicker' => ['Backend\View\Widget\DatePickerWidget'],
            'timepicker' => ['Backend\View\Widget\TimePickerWidget'],
            'imageselect' => ['Backend\View\Widget\ImageSelectWidget'],
            'imagemodal' => ['Backend\View\Widget\ImageModalWidget'],
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

    protected function _getInput($fieldName, $options)
    {
        if (isset($options['type'])) {
            switch($options['type']) {
                case 'select':
                    //$this->_View->loadHelper('Backend.Chosen');
                    break;

                case 'datepicker':
                    $this->Html->css('Backend.pickadate/themes/classic', ['block' => true]);
                    $this->Html->css('Backend.pickadate/themes/classic.date', ['block' => true]);
                    $this->Html->css('Backend.pickadate/themes/classic.time', ['block' => true]);
                    $this->Html->script('Backend.pickadate/picker', ['block' => 'scriptBottom']);
                    $this->Html->script('Backend.pickadate/picker.date', ['block' => 'scriptBottom']);
                    $this->Html->script('Backend.pickadate/picker.time', ['block' => 'scriptBottom']);
                    break;

                case 'htmleditor':
                case 'htmltext':
                    $this->Html->script('_tinymce', ['block' => 'scriptBottom']);
                    break;

                case 'imageselect':
                case 'imagemodal':
                    $this->Html->css('Backend.imagepicker/image-picker', ['block' => true]);
                    $this->Html->script('Backend.imagepicker/image-picker.min', ['block' => 'scriptBottom']);
                    break;
            }
        }

        return parent::_getInput($fieldName, $options);
    }
}