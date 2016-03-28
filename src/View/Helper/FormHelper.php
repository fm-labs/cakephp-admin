<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 3/28/16
 * Time: 11:02 AM
 */

namespace Backend\View\Helper;

use Cake\View\Helper\FormHelper as CakeFormHelper;

class FormHelper extends CakeFormHelper
{
    private $_fieldsetOptions = [];

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