<?php
namespace Backend\View\Widget;

use Bootstrap\View\Widget\BasicWidget;
use Cake\View\Form\ContextInterface;
use Cake\View\View;
use Cake\View\Widget\CheckboxWidget;

class SwitchControlWidget extends CheckboxWidget
{
    /**
     * Constructor.
     *
     * @param \Cake\View\StringTemplate $templates Templates list.
     */
    public function __construct($templates, View $view)
    {
        parent::__construct($templates);

        //$view->loadHelper('Backend.SwitchControl');
    }

    public function render(array $data, ContextInterface $context)
    {
        if (!isset($data['id'])) {
            $data['id'] = uniqid('switchctrl');
        }

        $data['type'] = 'checkbox';
        $html = parent::render($data, $context);

        $switch = [];
        $tmpl = '<script>$(document).ready(function() { $(\'#%s\').bootstrapSwitch(%s); });</script>';
        $js = sprintf($tmpl, $data['id'], json_encode($switch));

        $hidden = new BasicWidget($this->_templates);
        $hiddenField = $hidden->render(['id' => false, 'type' => 'hidden', 'val' => '0', 'name' => $data['name']], $context);

        return $hiddenField . $html . $js;
    }
}
