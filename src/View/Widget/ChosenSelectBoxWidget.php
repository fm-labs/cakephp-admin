<?php
namespace Backend\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\View;
use Cake\View\Widget\SelectBoxWidget;

class ChosenSelectBoxWidget extends SelectBoxWidget
{
    /**
     * Constructor.
     *
     * @param \Cake\View\StringTemplate $templates Templates list.
     */
    public function __construct($templates, View $view)
    {
        parent::__construct($templates);

        $view->loadHelper('Backend.Chosen');
    }

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
            'no_results_text' => __d('backend','No results match'),
            'placeholder_text_multiple' => __d('backend','Select Some Options'),
            'placeholder_text_single' => __d('backend','Select an Option')
        ];

        if (isset($data['chosen'])) {
            $chosen = array_merge($chosen, (array)$data['chosen']);
        }

        $js = sprintf("<script>$(document).ready(function() { $('#%s').chosen(%s); });</script>", $data['id'], json_encode($chosen));

        return $html . $js;
    }
}
