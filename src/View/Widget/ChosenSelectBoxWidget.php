<?php
namespace Backend\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\SelectBoxWidget;

class ChosenSelectBoxWidget extends SelectBoxWidget
{
    public function render(array $data, ContextInterface $context)
    {
        if (!isset($data['id'])) {
            $data['id'] = uniqid('select');
        }

        $html = parent::render($data, $context);

        $chosen = [
            'allow_single_deselect' => true,
            'search_contains' => true,
            'inherit_select_classes' => true,
            'width' => '100%',
            'disable_search' => false,
            'disable_search_threshold' => 10,
            'no_results_text' => __('No results match'),
            'placeholder_text_multiple' => __('Select Some Options'),
            'placeholder_text_single' => __('Select an Option')
        ];

        if (isset($data['chosen'])) {
            $chosen = array_merge($chosen, (array) $data['chosen']);
        }

        $js = sprintf('<script>$(document).ready(function() { $("#%s").chosen(%s); });</script>', $data['id'], json_encode($chosen));

        return $html . $js;
    }
}