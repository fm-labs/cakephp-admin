<?php

namespace Backend\Action;

use Backend\Action\Interfaces\TableActionInterface;
use Cake\Controller\Controller;
use Cake\Event\EventListenerInterface;
use Cake\Network\Exception\NotImplementedException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

abstract class BaseTableAction implements TableActionInterface, EventListenerInterface
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'actions' => [],
        'rowActions' => []
    ];

    /**
     * @var array
     */
    protected $_config = [];

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        $class = explode('\\', get_class($this));
        $class = array_pop($class);
        $name = substr($class, 0, -6);
        return Inflector::humanize($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return [];
    }
    /**
     * {@inheritDoc}
     */
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

        // actions
        if ($this->_config['actions'] !== false) {
            $event = $controller->dispatchEvent('Backend.Table.Actions.get');
            $this->_config['actions'] = (array)$event->result;
        }

        if ($this->_config['rowActions'] !== false) {
            $event = $controller->dispatchEvent('Backend.Table.RowActions.get');
            $this->_config['rowActions'] = (array)$event->result;
        }

        return $this->_execute($controller);
    }

    protected function _execute(Controller $controller)
    {
        throw new NotImplementedException(get_class($this) . ' has no _execute() method implemented');
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
