<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;
use Cake\Utility\Inflector;

class InlineEntityAction extends BaseEntityAction
{
    protected $_scope = [];
    protected $_attributes = [];

    public $action;
    public $options;

    public function __construct($action, array $options = [])
    {
        $this->action = $action;

        $options += ['form' => null, 'label' => null, 'scope' => [], 'attrs' => []];
        $this->options = $options;
        $this->_scope = $this->options['scope'];
        $this->_attributes = $this->options['attrs'];
    }

    public function getAlias()
    {
        return Inflector::underscore($this->action);
    }

    public function getLabel()
    {
        if ($this->options['label']) {
            return $this->options['label'];
        }
        return Inflector::humanize(Inflector::underscore($this->action));
    }

    public function getAttributes()
    {
        return $this->_attributes;
    }

    public function hasForm()
    {
        return ($this->options['form'] === true);
    }

    public function isUsable(EntityInterface $entity)
    {
        return true;
    }

    protected function _execute(Controller $controller)
    {
        $entity = $this->entity();
        return call_user_func([$controller, $this->action], $entity->id);
    }
}