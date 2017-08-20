<?php

namespace Backend\Action;

use Backend\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Network\Exception\NotImplementedException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

/**
 * Class BaseIndexAction
 *
 * @package Backend\Action
 */
abstract class BaseIndexAction implements IndexActionInterface, EventListenerInterface
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
    public function getAlias()
    {
        $class = explode('\\', get_class($this));
        $class = array_pop($class);
        return substr($class, 0, -6);
    }

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        $alias = $this->getAlias();
        return Inflector::humanize($alias);
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'list'];
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
            $event = $controller->dispatchEvent('Backend.Controller.buildIndexActions', ['actions' => $this->_config['actions']]);
            $this->_config['actions'] = (array)$event->data['actions'];
        }


        //if ($this->_config['rowActions'] !== false) {
        //    $event = $controller->dispatchEvent('Backend.Action.Index.getRowActions', ['actions' => $this->_config['rowActions']]);
        //    $this->_config['rowActions'] = (array)$event->data['actions'];
        //}

        return $this->_execute($controller);
    }

    /**
     * @param Controller $controller
     */
    protected function _execute(Controller $controller)
    {
        throw new NotImplementedException(get_class($this) . ' has no _execute() method implemented');
    }

    /**
     * @return bool|\Cake\ORM\Table
     */
    public function model()
    {
        if (!$this->_config['modelClass']) {
            //throw new \Exception(get_class($this) . ' has no model class defined');
            return false;
        }

        return TableRegistry::get($this->_config['modelClass']);
    }


    /**
     * @param Event $event
     */
    public function buildIndexActions(Event $event)
    {
    }

    /**
     * @param Event $event
     */
    public function buildEntityActions(Event $event)
    {
    }

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Backend.Controller.buildIndexActions' => [ 'callable' => 'buildIndexActions', 'priority' => 5 ],
            'Backend.Controller.buildEntityActions' => [ 'callable' => 'buildEntityActions', 'priority' => 5 ]
        ];
    }
}
