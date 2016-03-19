<?php
namespace Backend\View\Widget;

use Cake\View\Widget\ButtonWidget as CakeButtonWidget;
use Cake\View\Form\ContextInterface;

class ButtonWidget extends CakeButtonWidget
{

    public function render(array $data, ContextInterface $context)
    {
        $data['class'] = (isset($data['class'])) ? 'ui button ' . $data['class'] : 'ui button';

        return parent::render($data, $context);
    }

}
