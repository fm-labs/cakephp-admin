<?php

namespace Backend\View\Widget;

use Cake\View\Widget\DateTimeWidget as CakeDateTimeWidget;
use Cake\View\Form\ContextInterface;
use Cake\View\StringTemplate;
use DateTime;

/**
 * Class TimePickerWidget
 *
 * @package Backend\View\Widget
 */
class TimePickerWidget extends CakeDateTimeWidget
{
    /**
     * @param StringTemplate $templates
     */
    public function __construct(StringTemplate $templates)
    {
        $this->_templates = $templates;
    }

    /**
     * {@inheritDoc}
     */
    public function render(array $data, ContextInterface $context)
    {
        $_data = [
            'type' => 'text',
            'escape' => true,
            'class' => 'timepicker',
            'options' => $data['options'],
            'id' => $data['id'],
            'name' => $data['name'],
            'val' => $data['val']
        ];

        if ($_data['val']) {
            if (!is_object($_data['val'])) {
                $_data['val'] = new DateTime($_data['val']);
            }
            $_data['data-value'] = $_data['value'] = date("H:i", $_data['val']->getTimestamp());
        }
        unset($_data['val']);
        unset($_data['options']);

        $pickerOptions = [
            'format' => 'h:i a',
            'formatLabel' => '<b>h</b>:i <!i>a</!i>',
            'formatSubmit' => 'HH:ii',
            'hiddenPrefix' => 'pickatime__',
            'hiddenSuffix' => null
        ];

        $this->_templates->add([
            'timepicker' => '<input type="{{type}}" name="{{name}}"{{attrs}}>',
            'timepicker_script' => '<script>$("{{selector}}").pickadate({{picker}})</script>'
        ]);

        $html = $this->_templates->format('timepicker', [
            'name' => $_data['name'],
            'type' => $_data['type'],
            'attrs' => $this->_templates->formatAttributes(
                $_data,
                ['name', 'type']
            ),
        ]);

        $script = $this->_templates->format('timepicker_script', [
            'selector' => '#' . $_data['id'],
            'picker' => json_encode($pickerOptions),
        ]);

        return $html . $script;
    }
}
