<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Controller\Exception\MissingActionException;
use Cake\Datasource\EntityInterface;
use Cake\Utility\Inflector;

class InlineEntityAction extends BaseEntityAction
{
    protected $_scope = [];
    protected $_attributes = [];
    protected $_callable;

    public $action;
    public $options;

    public function __construct(Controller $controller, array $options = [], callable $callable = null)
    {
        parent::__construct($controller, []);
        $options += ['action' => null, 'form' => null, 'label' => null, 'scope' => [], 'attrs' => []];

        $this->action = $options['action'];
        $this->options = $options;
        $this->_scope = $this->options['scope'];
        $this->_attributes = $this->options['attrs'];

        if ($this->_callable === null) {
            $callable = [$controller, $this->action];
        }
        $this->_callable = $callable;
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
//        if (!method_exists($controller, $this->action)) {
//            throw new MissingActionException([
//                'controller' => $controller->name,
//                'action' => $this->action,
//                'prefix' => isset($controller->request->params['prefix']) ? $controller->request->params['prefix'] : '',
//                'plugin' => $controller->request->params['plugin'],
//            ]);
//        }

        if (!is_callable($this->_callable)) {
            throw new \InvalidArgumentException("InlineAction " . $this->action . " has no valid callback");
        }

        //$this->_config['modelId'] = $controller->request->params[0];
        //$entity = $this->entity();
        //$controller->set('entity', $entity);
        return call_user_func_array($this->_callable, $controller->request->params);
    }
}