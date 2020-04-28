<?php
declare(strict_types=1);

namespace Admin\View\Widget;

use Cake\View\Form\ContextInterface;

class CheckboxWidget extends \Cake\View\Widget\CheckboxWidget
{
    /**
     * Constructor.
     *
     * @param \Cake\View\StringTemplate $templates Templates list.
     */
    public function __construct($templates)
    {
        parent::__construct($templates);
    }

    /**
     * {@inheritDoc}
     */
    public function render(array $data, ContextInterface $context): string
    {
        //$this->_templates->add()

        $config['templates'] = [
            'checkboxFormGroup' => '<div class="checkbox">{{label}}</div>',
            'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}} /><span class="checkmark"></span>',
            //'inputContainerError' => '<div class="form-group has-error input-{{type}}{{required}}">{{error}}{{content}}</div>',
        ];
        $config['templatesHorizontal'] = [
            'checkbox' => '<div class="checkbox"><label><input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}} /><span class="checkmark"></span></label></div>',
        ];

        $this->_templates->push();
        $this->_templates->add($config['templatesHorizontal']);

        $data['type'] = 'checkbox';
        //$data['hiddenField'] = '_split';
        //$data['nestedInput'] = true;

        $html = parent::render($data, $context);
        $this->_templates->pop();

        return $html;
    }
}
