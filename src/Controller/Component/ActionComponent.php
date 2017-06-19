<?php

namespace Backend\Controller\Component;

use Backend\Action\ActionRegistry;
use Backend\Action\Interfaces\EntityActionInterface;
use Backend\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Network\Response;
use Cake\Utility\Inflector;

/**
 * Class ActionComponent
 *
 * @package Backend\Controller\Component
 */
class ActionComponent extends Component
{
    /**
     * @var array List of configured actions
     */
    public $actions = [];

    /**
     * @var ActionRegistry
     */
    protected $_actionRegistry;

    /**
     * @var Controller
     */
    protected $_controller;

    /**
     * Initialize actions
     * @param array $config
     * @return void
     */
    public function initialize(array $config)
    {
        $this->_controller = $this->_registry->getController();
        $this->_actionRegistry = new ActionRegistry();

        // use controller actions, if defined
        $actions = [];
        if (isset($this->_controller->actions)) {
            $actions = $this->_controller->actions;
        }

        // normalize action configs and add actions to actions registry
        foreach ($actions as $action => $actionConfig) {
            if (is_string($actionConfig)) {
                $actionConfig = ['className' => $actionConfig];
            }
            $this->_actionRegistry->load($action, $actionConfig);
            $this->actions[$action] = $actionConfig;
        }
    }

    /**
     * @param $action
     * @return bool
     */
    public function hasAction($action)
    {
        return $this->_actionRegistry->has($action);
    }

    /**
     * @param $action
     * @return null|object
     */
    public function getAction($action)
    {
        return $this->_actionRegistry->get($action);
    }

    /**
     * @return array
     */
    public function listActions()
    {
        return $this->_actionRegistry->loaded();
    }

    /**
     * @param $action
     * @return array
     */
    public function getActionUrl($action)
    {
        $actionObj = $this->getAction($action);
        if ($actionObj instanceof EntityActionInterface) {
            return ['action' => $action, ':id'];
        } else {
            return ['action' => $action];
        }
    }

    /**
     * @param null $action
     * @return mixed
     * @deprecated Use execute() method instead
     */
    public function executeAction($action = null)
    {
        return $this->execute($action);
    }

    /**
     * @param $action
     * @return mixed
     */
    public function execute($action = null)
    {
        if ($action === null) {
            $action = $this->request->params['action'];
        }

        if ($this->_actionRegistry->has($action)) {
            $config = $this->actions[$action];
            $actionObj = $this->_actionRegistry->get($action);

            $event = $this->_registry->getController()->dispatchEvent('Backend.beforeAction', [ 'name' => $action, 'action' => $actionObj ]);
            if ($event->result instanceof Response) {
                return $event->result;
            }

            //@TODO Refactor with ActionView
            //--
            $templatePath = 'Action';
            if ($this->request->params['prefix']) {
                $templatePath = Inflector::camelize($this->request->params['prefix']) . '/' . $templatePath;
            }

            list($plugin, ) = pluginSplit($config['className']);
            $template = ($plugin) ? $plugin . '.' . $action : $action;

            $this->_controller->viewBuilder()->templatePath($templatePath);
            $this->_controller->viewBuilder()->template($template);
            //--

            // attach Action instance to controllers event manager
            if ($actionObj instanceof EventListenerInterface) {
                $this->_controller->eventManager()->on($actionObj);
            }

            $response = $actionObj->execute($this->_controller);

            // detach Action instance from controllers event manager
            if ($actionObj instanceof EventListenerInterface) {
                $this->_controller->eventManager()->off($actionObj);
            }

            $event = $this->_registry->getController()->dispatchEvent('Backend.afterAction', [ 'name' => $action, 'action' => $actionObj ]);
            if ($event->result instanceof Response) {
                return $event->result;
            }

            return $response;
        }

        throw new \RuntimeException('Action ' . $action . ' not loaded');
    }

    /**
     * @param Event $event
     */
    public function getIndexActions(Event $event)
    {
        foreach ($this->listActions() as $action) {
            $_action = $this->getAction($action);
            if ($action == "index" || (!($_action instanceof IndexActionInterface))) {
                continue;
            }
            $event->result[] = [$_action->getLabel(), ['action' => $action], $_action->getAttributes()];
        }
    }

    /**
     * @param Event $event
     */
    public function getIndexRowActions(Event $event)
    {
        foreach ($this->listActions() as $action) {
            $_action = $this->getAction($action);
            if ($action == "index" || (!($_action instanceof EntityActionInterface))) {
                continue;
            }
            $event->result[] = [$_action->getLabel(), ['action' => $action, ':id'], $_action->getAttributes()];
        }
    }

    /**
     * @param Event $event
     */
    public function getEntityActions(Event $event)
    {
        $entity = $event->data['entity'];
        foreach ($this->listActions() as $action) {
            $_action = $this->getAction($action);
            if (($_action instanceof IndexActionInterface)) {
                $event->result[] = [$_action->getLabel(), ['action' => $action]];
            }
            if (($_action instanceof EntityActionInterface)) {
                $event->result[] = [$_action->getLabel(), ['action' => $action, $entity->id]];
            }
        }
    }

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            //'Controller.initialize' => 'beforeFilter',
            //'Controller.startup' => 'startup',
            //'Controller.beforeRender' => 'beforeRender',
            //'Controller.beforeRedirect' => 'beforeRedirect',
            //'Controller.shutdown' => 'shutdown',
            'Backend.Action.Index.getActions' => [ 'callable' => 'getIndexActions', 'priority' => 5 ],
            'Backend.Action.Index.getRowActions' => [ 'callable' => 'getIndexRowActions', 'priority' => 5 ],
            'Backend.Action.Entity.getActions' => [ 'callable' => 'getEntityActions', 'priority' => 5 ]
        ];
    }
}
