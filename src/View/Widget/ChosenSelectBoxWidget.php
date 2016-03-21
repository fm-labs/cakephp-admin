<?php
namespace Backend\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\SelectBoxWidget;

class ChosenSelectBoxWidget extends SelectBoxWidget
{
    public function render(array $data, ContextInterface $context)
    {
        //$data['class'] = (isset($data['class'])) ? $data['class'] . ' chosen-select' : 'chosen-select';


        if (!isset($data['id'])) {
            $data['id'] = uniqid('select');
        }

        $html = parent::render($data, $context);

        $chosen = [
            'search_contains' => true,
            'inherit_select_classes' => true,
            'width' => '100%'
        ];

        $js = sprintf("<script>$('#%s').chosen(%s)</script>", $data['id'], json_encode($chosen));

        return $html . $js;
    }
}