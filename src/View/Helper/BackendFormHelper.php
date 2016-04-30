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
}