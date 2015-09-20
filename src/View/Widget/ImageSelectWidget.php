<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 9/6/15
 * Time: 1:22 AM
 */

namespace Backend\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\SelectBoxWidget;
use Cake\View\Widget\WidgetInterface;

class ImageSelectWidget extends SelectBoxWidget
{
    public function render(array $data, ContextInterface $context)
    {
        $data += [
            'name' => '',
            'empty' => false,
            'escape' => true,
            'options' => [],
            'disabled' => null,
            'val' => null,
        ];

        $options = $this->_renderContent($data);
        $name = $data['name'];
        unset($data['name'], $data['options'], $data['empty'], $data['val'], $data['escape']);
        if (isset($data['disabled']) && is_array($data['disabled'])) {
            unset($data['disabled']);
        }

        $this->_templates->add([
            'imageselect' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',
            'imageselectMultiple' => '<select name="{{name}}[]" multiple="multiple"{{attrs}}>{{content}}</select>',
        ]);

        $class = 'imagepicker nochosen';
        $template = 'imageselect';
        if (!empty($data['multiple'])) {
            $class = 'imagepicker-multi';
            $template = 'imageselectMultiple';
            unset($data['multiple']);
        }

        if (isset($data['class'])) {
            $class .= ' ' . $data['class'];
        }
        $data['class'] = $class;

        $attrs = $this->_templates->formatAttributes($data);
        return $this->_templates->format($template, [
            'name' => $name,
            'attrs' => $attrs,
            'content' => implode('', $options),
        ]);
    }

    protected function _renderOptions($options, $disabled, $selected, $templateVars, $escape)
    {
        $out = [];
        foreach ($options as $key => $val) {
            // Option groups
            $arrayVal = (is_array($val) || $val instanceof Traversable);
            if ((!is_int($key) && $arrayVal) ||
                (is_int($key) && $arrayVal && isset($val['options']))
            ) {
                $out[] = $this->_renderOptgroup($key, $val, $disabled, $selected, $escape);
                continue;
            }

            // Basic options
            $optAttrs = [
                'value' => $key,
                'text' => $val,
            ];
            if (is_array($val) && isset($optAttrs['text'], $optAttrs['value'])) {
                $optAttrs = $val;
            }
            if ($this->_isSelected($key, $selected)) {
                $optAttrs['selected'] = true;
            }
            if ($this->_isDisabled($key, $disabled)) {
                $optAttrs['disabled'] = true;
            }
            $optAttrs['escape'] = $escape;

            $optAttrs['data-img-src'] = $optAttrs['text'];
            $optAttrs['data-img-label'] = basename($optAttrs['text']);
            $optAttrs['title'] = basename($optAttrs['text']);

            $out[] = $this->_templates->format('option', [
                'value' => $escape ? h($optAttrs['value']) : $optAttrs['value'],
                'text' => $escape ? h($optAttrs['text']) : $optAttrs['text'],
                'attrs' => $this->_templates->formatAttributes($optAttrs, ['text', 'value']),
            ]);
        }
        return $out;
    }
}
