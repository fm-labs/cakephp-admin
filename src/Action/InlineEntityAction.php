<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;
use Cake\Utility\Inflector;

class InlineEntityAction extends BaseEntityAction
{
    protected $_scope = [];
    protected $_attributes = [];
    protected $_callable;
    protected $_executed = false;
    protected $_filter;

    public $action;
    public $options;

    public function __construct(Controller $controller, array $options = [], callable $callable = null, callable $filter = null)
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
        $this->_filter = null;
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

    protected function _execute(Controller $controller)
    {
        //if ($this->_executed == true) {
        //    return;
        //}
//        if (!method_exists($controller, $this->action)) {
//            throw new MissingActionException([
//                'controller' => $controller->name,
//                'action' => $this->action,
//                'prefix' => isset($controller->request->params['prefix']) ? $controller->request->params['prefix'] : '',
//                'plugin' => $controller->request->params['plugin'],
//            ]);
//        }
//
//        if (!is_callable($this->_callable)) {
//            throw new \InvalidArgumentException("InlineAction " . $this->action . " has no valid callback");
//        }

        //$this->_config['modelId'] = $controller->request->params['pass'][0];
        //$entity = $this->entity();
        //$controller->set('entity', $entity);
        //$this->_executed = true;
        return call_user_func_array($this->_callable, $controller->request->params['pass']);
    }
}
