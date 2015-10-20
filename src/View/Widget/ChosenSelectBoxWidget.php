<?php
namespace Backend\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\SelectBoxWidget;

class ChosenSelectBoxWidget extends SelectBoxWidget
{
    public function render(array $data, ContextInterface $context)
    {
        $data['class'] = (isset($data['class'])) ? $data['class'] . ' chosen-select' : 'chosen-select';
        return parent::render($data, $context);
    }
}