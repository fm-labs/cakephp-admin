<?php

namespace Backend\Action;


use Cake\Controller\Controller;
use Cake\Core\Plugin;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

abstract class BaseAction implements ActionInterface, EventListenerInterface
{
    /**
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * @var array
     */
    protected $_config = [];

    public function execute(Controller $controller)
    {
        // read config from controller view vars
        foreach (array_keys($this->_defaultConfig) as $key) {
            $this->_config[$key] = (isset($controller->viewVars[$key]))
                ? $controller->viewVars[$key]
                : $this->_defaultConfig[$key];
        }

        // detect model class
        if (!isset($controller->viewVars['modelClass'])) {
            $this->_config['modelClass'] = $controller->modelClass;
        }

        // load helpers
        if (isset($controller->viewVars['helpers'])) {
            $controller->viewBuilder()->helpers($controller->viewVars['helpers'], true);
        }

        return $this->_execute($controller);
    }

    protected function _execute(Controller $controller)
    {
        throw new \Exception(get_class($this) . ' has no _execute() method implemented');
    }

    public function model()
    {
        if (!$this->_config['modelClass']) {
            //throw new \Exception(get_class($this) . ' has no model class defined');
            return false;
        }

        return TableRegistry::get($this->_config['modelClass']);
    }

    public function implementedEvents()
    {
        return [];
    }
}