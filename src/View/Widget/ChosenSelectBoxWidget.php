<?php
declare(strict_types=1);

namespace Backend\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\View;
use Cake\View\Widget\SelectBoxWidget;

class ChosenSelectBoxWidget extends SelectBoxWidget
{
    /**
     * {@inheritDoc}
     */
    public function __construct($templates, View $view)
    {
        parent::__construct($templates);

        $view->loadHelper('Backend.Chosen');
    }

    /**
     * {@inheritDoc}
     */
    public function render(array $data, ContextInterface $context): string
    {
        if (!isset($data['id'])) {
            $data['id'] = uniqid('select');
        }

        $chosen = [
            'allow_single_deselect' => true,
            'search_contains' => true,
            'inherit_select_classes' => true,
            'width' => '100%',
            'disable_search' => false,
            'disable_search_threshold' => 10,
            'no_results_text' => __d('backend', 'No results match'),
            'placeholder_text_multiple' => __d('backend', 'Select Some Options'),
            'placeholder_text_single' => __d('backend', 'Select an Option'),
        ];

        if (isset($data['chosen'])) {
            $chosen = array_merge($chosen, (array)$data['chosen']);
            unset($data['chosen']);
        }

        if (isset($data['empty']) && is_string($data['empty'])) {
            $chosen['placeholder_text_single'] = $data['empty'];
            $data['empty'] = true;
        }

        $html = parent::render($data, $context);
        $js = sprintf("<script>$(document).ready(function() { $('#%s').chosen(%s); });</script>", $data['id'], json_encode($chosen));

        return $html . $js;
    }
}
