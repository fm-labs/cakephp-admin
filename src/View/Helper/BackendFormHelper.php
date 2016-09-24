<?php

namespace Backend\View\Helper;

use Cake\View\Helper\FormHelper as CakeFormHelper;

class BackendFormHelper extends CakeFormHelper
{
    private $_fieldsetOptions = [];

    /*
    public function label($fieldName, $text = null, array $options = [])
    {
        if (!isset($options['class'])) {
            $options['class'] = 'control-label';
        }

        return parent::label($fieldName, $text, $options);
    }
    */

    public function fieldsetStart($options = [])
    {
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