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
     * @var Controller Active controller
     */
    protected $_controller;

    /**
     * @var EntityActionInterface|object Active action
     */
    protected $_action;

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

        // detect actions from model
        $this->_initModelActions($actions);

        // normalize action configs and add actions to actions registry
        foreach ($actions as $action => $actionConfig) {
            if ($actionConfig === false) {
                continue;
            }
            if (is_string($actionConfig)) {
                $actionConfig = ['className' => $actionConfig];
            }
            $this->_actionRegistry->load($action, $actionConfig);
            $this->actions[$action] = $actionConfig;
        }
    }

    protected function _initModelActions(&$actions)
    {
        $modelClass = $this->_controller->modelClass;
        if ($modelClass) {
            $Model = $this->_controller->loadModel($modelClass);

            $event = $this->_registry->getController()->dispatchEvent('Backend.Controller.setupActions', [
                'modelClass' => $modelClass,
                'model' => $Model,
                'actions' => $actions
            ]);

            $actions = $event->data['actions'];
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
            $this->_action = $actionObj = $this->_actionRegistry->get($action);

            $event = $this->_registry->getController()->dispatchEvent('Backend.beforeAction', [ 'name' => $action, 'action' => $actionObj ]);
            if ($event->result instanceof Response) {
                return $event->result;
            }

            //@TODO Refactor with ActionView
            //--
            if ($this->_controller->noActionTemplate === false && !isset($this->_action->noTemplate)) {
                $templatePath = 'Action';
                if ($this->request->params['prefix']) {
                    $templatePath = Inflector::camelize($this->request->params['prefix']) . '/' . $templatePath;
                }

                list($plugin, ) = pluginSplit($config['className']);
                $template = ($plugin) ? $plugin . '.' . $action : $action;

                $this->_controller->viewBuilder()->templatePath($templatePath);
                $this->_controller->viewBuilder()->template($template);
            }
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
    public function buildIndexActions(Event $event)
    {
        foreach ($this->listActions() as $action) {
            $_action = $this->getAction($action);
            if ($action == "index" || (!($_action instanceof IndexActionInterface))) {
                continue;
            }
            $event->data['actions'][$action] = [$_action->getLabel(), ['action' => $action], $_action->getAttributes()];
        }
    }

    /**
     * @param Event $event
     */
    public function buildEntityActions(Event $event)
    {
        //$entity = (isset($event->data['entity'])) ? $event->data['entity'] : null;
        foreach ($this->listActions() as $action) {
            $_action = $this->getAction($action);
            if ($_action == $this->_action) {
                continue;
            }

            if ($action == "index") {
                $event->data['actions'][$action] = [$_action->getLabel(), ['action' => $action], $_action->getAttributes()];
            }
            //elseif ($entity && ($_action instanceof EntityActionInterface)) {
            //    $event->data['actions'][$action] = [$_action->getLabel(), ['action' => $action, $entity->id], $_action->getAttributes()];
            //}
            elseif ($_action instanceof EntityActionInterface) {
                $event->data['actions'][$action] = [$_action->getLabel(), ['action' => $action, ':id'], $_action->getAttributes()];
            }
        }

        if (method_exists($event->subject(), 'buildEntityActions')) {
            call_user_func([$event->subject(), 'buildEntityActions'], $event);
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
            'Backend.Controller.buildIndexActions' => [ 'callable' => 'buildIndexActions', 'priority' => 4 ],
            'Backend.Controller.buildEntityActions' => [ 'callable' => 'buildEntityActions', 'priority' => 4 ]
        ];
    }
}
