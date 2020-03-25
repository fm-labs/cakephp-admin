<?php
declare(strict_types=1);

namespace Backend\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\SelectBoxWidget;
use Media\Lib\Media\MediaManager;
use Traversable;

class ImageSelectWidget extends SelectBoxWidget
{
    /**
     * {@inheritDoc}
     */
    public function render(array $data, ContextInterface $context)
    {
        //return parent::render($data, $context);

        $data += [
            'name' => '',
            'empty' => false,
            'escape' => true,
            'config' => null,
            'options' => [],
            'disabled' => null,
            'val' => null,
        ];

        if (empty($data['options']) && isset($data['config'])) {
            $data['options'] = MediaManager::get($data['config'])->getSelectListRecursiveGrouped();
        } elseif (is_string($data['options']) && preg_match('/^\@(.*)/', $data['options'])) {
            $mediaConfig = substr($data['options'], 1);
            $data['options'] = MediaManager::get($mediaConfig)->getSelectListRecursiveGrouped();
        }

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

        $class = 'imagepicker';
        $template = 'imageselect';
        if (!empty($data['multiple'])) {
            $class = 'imagepicker multi';
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

    /**
     * {@inheritDoc}
     */
    protected function _renderOptions($options, $disabled, $selected, $templateVars, $escape)
    {
        $out = [];
        foreach ($options as $key => $val) {
            // Option groups
            $arrayVal = (is_array($val) || $val instanceof Traversable);
            if (
                (!is_int($key) && $arrayVal) ||
                (is_int($key) && $arrayVal && isset($val['options']))
            ) {
                $out[] = $this->_renderOptgroup($key, $val, $disabled, $selected, $templateVars, $escape);
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

            if (!empty($key)) {
                $optAttrs['data-img-src'] = $optAttrs['text'];
                $optAttrs['data-img-label'] = basename($optAttrs['text']);
                $optAttrs['text'] = $key;
                $optAttrs['title'] = basename($optAttrs['text']);
            }

            $out[] = $this->_templates->format('option', [
                'value' => $escape ? h($optAttrs['value']) : $optAttrs['value'],
                'text' => $escape ? h($optAttrs['text']) : $optAttrs['text'],
                'attrs' => $this->_templates->formatAttributes($optAttrs, ['text', 'value']),
            ]);
        }

        return $out;
    }

    /**
     * Render the contents of an optgroup element.
     *
     * @param string $label The optgroup label text
     * @param array $optgroup The opt group data.
     * @param array|null $disabled The options to disable.
     * @param array|string|null $selected The options to select.
     * @param array $templateVars Additional template variables.
     * @param bool $escape Toggle HTML escaping
     * @return string Formatted template string
     */
    protected function _renderOptgroup($label, $optgroup, $disabled, $selected, $templateVars, $escape)
    {
        $opts = $optgroup;
        $attrs = [];
        if (isset($optgroup['options'], $optgroup['text'])) {
            $opts = $optgroup['options'];
            $label = $optgroup['text'];
            $attrs = $optgroup;
        }
        $groupOptions = $this->_renderOptions($opts, $disabled, $selected, $templateVars, $escape);

        return $this->_templates->format('optgroup', [
            'label' => $escape ? h($label) : $label,
            'content' => implode('', $groupOptions),
            'templateVars' => $templateVars,
            'attrs' => $this->_templates->formatAttributes($attrs, ['text', 'options']),
        ]);
    }
}
