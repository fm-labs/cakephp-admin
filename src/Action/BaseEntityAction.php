<?php

namespace Backend\Action;

use Backend\Action\Interfaces\EntityActionInterface;
use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Network\Exception\NotImplementedException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

abstract class BaseEntityAction implements EntityActionInterface, EventListenerInterface
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'actions' => []
    ];

    /**
     * @var array
     */
    protected $_config = [];

    /**
     * @var EntityInterface
     */
    protected $_entity;

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

    public function execute(Controller $controller)
    {
        // read config from controller view vars
        foreach (array_keys($this->_defaultConfig) as $key) {
            $this->_config[$key] = (isset($controller->viewVars[$key]))
                ? $controller->viewVars[$key]
                : $this->_defaultConfig[$key];
        }

        // detect model class and load entity
        if (!isset($controller->viewVars['modelClass'])) {
            $this->_config['modelClass'] = $controller->modelClass;
        }
        if (!isset($controller->viewVars['modelId'])) {
            $modelId = $controller->request->param('id');
            $modelId = ($modelId) ?: $controller->request->param('pass')[0]; // @TODO request param 'pass' might be empty or unset
            $this->_config['modelId'] = $modelId;
        }

        try {
            $entity = $this->entity();

            // load helpers
            if (isset($controller->viewVars['helpers'])) {
                $controller->viewBuilder()->helpers($controller->viewVars['helpers'], true);
            }

            // actions
            if ($this->_config['actions'] !== false) {
                $event = $controller->dispatchEvent('Backend.Entity.Actions.get', ['entity' => $entity]);
                $this->_config['actions'] = (array)$event->result;
            }

            return $this->_execute($controller);
        } catch (\Exception $ex) {
            $controller->Flash->error($ex->getMessage());
            $controller->redirect($controller->referer());
        }
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

    public function entity()
    {
        if (!$this->_config['modelId']) {
            throw new \Exception(get_class($this) . ' has no model ID defined');
        }

        if (!$this->_entity) {
            $this->_entity = $this->model()->get($this->_config['modelId']);
        }

        return $this->_entity;
    }

    public function buildActions(Event $event)
    {

    }

    public function implementedEvents()
    {
        return ['Backend.Entity.Actions.get' => 'buildActions'];
    }
}
