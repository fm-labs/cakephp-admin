<?php
namespace Backend\View\Widget;

use Cake\View\Widget\ButtonWidget as CakeButtonWidget;
use Cake\View\Form\ContextInterface;

class ButtonWidget extends CakeButtonWidget
{

    public function render(array $data, ContextInterface $context)
    {
        $data['class'] = (isset($data['class'])) ? $data['class'] : 'btn btn-primary';

        return parent::render($data, $context);
    }

}
