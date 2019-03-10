<?php
namespace Backend\View\Widget;

use Cake\Core\Configure;
use Cake\View\Form\ContextInterface;
use Cake\View\View;
use Cake\View\Widget\BasicWidget;

class DateRangePickerWidget extends BasicWidget
{
    // momentjs compatible time formats
    const FORMAT_DATE_JS = 'YYYY-MM-DD';
    const FORMAT_DATETIME_JS = 'YYYY-MM-DD HH:mm';
    const FORMAT_DATETIME_12H_JS = 'YYYY-MM-DD hh:mm a';

    const FORMAT_DATE_PHP = 'Y-m-d';
    const FORMAT_DATETIME_PHP = 'Y-m-d H:i';
    const FORMAT_DATETIME_12H_PHP = 'Y-m-d h:i a';

    /**
     * {@inheritDoc}
     */
    public function __construct($templates, View $view)
    {
        parent::__construct($templates);

        $view->loadHelper('Backend.DateRangePicker');
    }

    /**
     * {@inheritDoc}
     */
    public function render(array $data, ContextInterface $context)
    {
        $data += [
            'id' => uniqid('daterangepicker'),
            'class' => 'form-control',
        ];

        // http://www.daterangepicker.com/#config
        $params = [
            'singleDatePicker' => false,
            'showDropdowns' => false,
            'showWeekNumbers' => false,
            'showISOWeekNumbers' => false,
            'timePicker' => false,
            'timePicker24Hour' => false,
            'timePickerSeconds' => false,
            'autoApply' => true,
            'autoUpdateInput' => true,
            'alwaysShowCalendars' => true,
            'locale' => [
                'format' => self::FORMAT_DATE_JS,
                'separator' => " - ",
                'applyLabel' => __('Apply'),
                'cancelLabel' => __('Cancel'),
                'fromLabel' => __('From'),
                'toLabel' => __('To'),
                'customRangeLabel' => __('Custom'),
                'weekLabel' => "W",
                'daysOfWeek' => [
                    __("Su"), __("Mo"), __("Tu"), __("We"), __("Th"), __("Fr"), __("Sa"),
                ],
                'monthNames' => [
                    __('January'), __('February'), __('March'), __('April'), __('May'), __('June'),
                    __('July'), __('August'), __('September'), __('October'), __('November'), __('December')
                ],
                'firstDay' => 1,
            ],
            //'minYear' => null,
            //'maxYear' => null,
            //'startDate' => null,
            //'endDate' => null,
            //'minDate' => null,
            //'maxDate' => null,
            'buttonClasses' => 'btn btn-sm',
            'applyButtonClasses' => 'btn-primary',
            'cancelClass' => 'btn-default',
            //'opens' => 'right',
            //'drops' => 'down'
        ];

        if (isset($data['picker'])) {
            $params = array_merge($params, $data['picker']);
            unset($data['picker']);
        }

        // timepicker specific
        if ($params['timePicker'] == true) {
            if (($params['timePicker24Hour']) == true) {
                $params['locale']['format'] = self::FORMAT_DATETIME_JS;
                $format = self::FORMAT_DATETIME_PHP;
            } else {
                $params['locale']['format'] = self::FORMAT_DATETIME_12H_JS;
                $format = self::FORMAT_DATETIME_12H_PHP;
            }
        } else {
            $format = self::FORMAT_DATE_PHP;
        }

        // parse the value
        $value = null;
        if ($data['val']) {
            $value = $data['val'];
        } elseif (isset($data['default'])) {
            $value = $data['default'];
        }

        $makeDate = function ($val) use ($format) {
            return (new \DateTime($val))->format($format);
        };

        if ($value) {
            $v = explode($params['locale']['separator'], $value);
            $v = array_map('trim', $v);
            if (count($v) == 2) {
                list($startDate, $endDate) = $v;
                $params['startDate'] = $makeDate($startDate);
                $params['endDate'] = $makeDate($endDate);
            } elseif (count($v) == 1) {
                list($startDate) = $v;
                //$params['singleDatePicker'] = true;
                $params['startDate'] = $makeDate($startDate);
            }
        }

        // custom ranges
        $defaultRanges = [
            'Today' => [(new \Cake\I18n\Date()), (new \Cake\I18n\Date())],
            'Yesterday' => [(new \Cake\I18n\Date())->subDays(1), (new \Cake\I18n\Date())->subDays(1)],
            'Last 7 Days' => [(new \Cake\I18n\Date())->subDays(6), (new \Cake\I18n\Date())],
            'Last 30 Days' => [(new \Cake\I18n\Date())->subDays(29), (new \Cake\I18n\Date())],
            'This month' => [(new \Cake\I18n\Date())->startOfMonth(), (new \Cake\I18n\Date())->endOfMonth()],
            'Last month' => [(new \Cake\I18n\Date())->subMonth(1), (new \Cake\I18n\Date())->startOfMonth()],
        ];
        if (isset($params['ranges']) && $params['ranges'] === true) {
            $params['ranges'] = $defaultRanges;
            //$params['alwaysShowCalendars'] = true;
        }

        $html = parent::render($data, $context);
        $jsTemplate = "<script>$(document).ready(function() { $('#%s').daterangepicker(%s); });</script>";
        $js = sprintf($jsTemplate, $data['id'], json_encode($params));

        return $html . $js;
    }
}
