<?php

namespace Backend\Controller\Component;

use Backend\Action\ActionRegistry;
use Backend\Action\ExternalEntityAction;
use Backend\Action\InlineEntityAction;
use Backend\Action\Interfaces\EntityActionInterface;
use Backend\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Network\Response;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\View\Exception\MissingTemplateException;

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
     * @var Table Active primary table
     */
    protected $_model;

    /**
     * Initialize actions
     * @param array $config
     * @return void
     */
    public function initialize(array $config)
    {
        $this->_controller = $this->_registry->getController();
        $this->_actionRegistry = new ActionRegistry($this->_controller);

        // use controller actions, if defined
        $actions = [];
        if (isset($this->_controller->actions)) {
            $actions = $this->_controller->actions;
        }

        // detect actions from model
        //$this->_initModelActions($actions);

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

    public function startup()
    {
        if (Configure::read('debug') && !isset($this->actions['debug'])) {
            $actionConfig = ['className' => 'Backend.Debug'];
            $this->_actionRegistry->load('debug', $actionConfig);
            $this->actions['debug'] = $actionConfig;
        }
    }

    /**
     * @deprecated Not in use
     */
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

    public function registerInline($action, array $options = [])
    {
        if ($action instanceof InlineEntityAction) {
            $instance = $action;
            $action = $instance->action;
        } else {
            $instance = new InlineEntityAction($action, $options);
        }
        $config = ['className' => $instance];
        $this->_actionRegistry->load($action, $config);
        $this->actions[$action] = [];
    }

    public function registerExternal($action, array $options = [])
    {
        if ($action instanceof ExternalEntityAction) {
            $instance = $action;
            $action = $instance->action;
        } else {
            $instance = new ExternalEntityAction($action, $options);
        }
        $config = ['className' => $instance];
        $this->_actionRegistry->load($action, $config);
        $this->actions[$action] = [];
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
     * @deprecated Not in use
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
            $this->_action = $this->_actionRegistry->get($action);
            $this->model(); // init primary model

            // attach Action instance to controllers event manager
            if ($this->_action instanceof EventListenerInterface) {
                $this->_controller->eventManager()->on($this->_action);
            }

            $event = $this->_registry->getController()->dispatchEvent('Backend.beforeAction', [ 'name' => $action, 'action' => $this->_action ]);
            if ($event->result instanceof Response) {
                return $event->result;
            }

            //@TODO Refactor with ActionView
            //--
            /*
            if ($this->_controller->noActionTemplate === false && !isset($this->_action->noTemplate)) {
                $templatePath = 'Action';
                if ($this->request->params['prefix']) {
                    $templatePath = Inflector::camelize($this->request->params['prefix']) . '/' . $templatePath;
                }

                $config = $this->actions[$action];
                list($plugin, ) = pluginSplit($config['className']);
                $template = ($plugin) ? $plugin . '.' . $action : $action;

                $this->_controller->viewBuilder()->templatePath($templatePath);
                $this->_controller->viewBuilder()->template($template);
            }
            */
            //--

            // execute the action in context of current controller
            $response = $this->_action->execute($this->_controller);

            // force rendering to capture template exception and fallback to Action template path
            if (!$response && $this->_controller->autoRender) {
                try {
                    $response = $this->_controller->render($this->_action->template);

                } catch (MissingTemplateException $ex) {

                    // Action template path
                    $templatePath = 'Action';
                    if ($this->request->params['prefix']) {
                        $templatePath = Inflector::camelize($this->request->params['prefix']) . '/' . $templatePath;
                    }

                    // check if action instance has a template defined
                    $template = $this->_action->template;

                    // use action class name as default template name
                    if (!$template) {
                        $config = $this->actions[$action];
                        list($plugin, $actionClass) = pluginSplit($config['className']);
                        //$template = ($plugin) ? $plugin . '.' . $action : $action;
                        $actionClass = Inflector::underscore($actionClass);
                        $template = ($plugin) ? $plugin . '.' . $actionClass : $actionClass;

                    }

                    $this->_controller->viewBuilder()->templatePath($templatePath);
                    $this->_controller->viewBuilder()->template($template);
                    $response = $this->_controller->render();
                }
            }

            $event = $this->_registry->getController()->dispatchEvent('Backend.afterAction', [ 'name' => $action, 'action' => $this->_action ]);
            if ($event->result instanceof Response) {
                return $event->result;
            }

            // detach Action instance from controllers event manager
            if ($this->_action instanceof EventListenerInterface) {
                //$this->_controller->eventManager()->off($this->_action);
            }

            return $response;
        }

        throw new \RuntimeException('Action ' . $action . ' not loaded');
    }

    public function model()
    {
        if (!$this->_model) {

            $modelClass = $this->_registry->getController()->modelClass;
            if (!$modelClass) {
                return null;
            }

            $this->_model = TableRegistry::get($modelClass);
        }

        return $this->_model;
    }

    /*
     * @deprecated
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

    /*
     * @deprecated
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

    public function beforeRender(Event $event) {

        $controller = $event->subject();

        // actions
        //@todo Move to ActionToolbar component
        $actions = [];
        if ($this->_action) {
            if ($this->_action instanceof EntityActionInterface) {

                $entity = $this->_action->entity();

                foreach ($this->listActions() as $action) {
                    $_action = $this->getAction($action);
                    if ($_action == $this->_action) {
                        continue;
                    }

                    if ($action == "index") {
                        $actions[$action] = [$_action->getLabel(), ['action' => $action], $_action->getAttributes()];
                    }
                    elseif ($_action instanceof EntityActionInterface && in_array('form', $_action->getScope()) && $_action->isUsable($entity)) {
                        $actions[$action] = [$_action->getLabel(), ['action' => $action, $entity->id], $_action->getAttributes()];
                    }
                }

            }
            elseif ($this->_action instanceof IndexActionInterface) {

                foreach ($this->listActions() as $action) {
                    $_action = $this->getAction($action);
                    if ($action == "index" || (!($_action instanceof IndexActionInterface))) {
                        continue;
                    }
                    $actions[$action] = [$_action->getLabel(), ['action' => $action], $_action->getAttributes()];
                }
            }
        }
        $controller->set('actions', $actions);
    }

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            //'Controller.initialize' => 'beforeFilter',
            'Controller.startup' => 'startup',
            'Controller.beforeRender' => 'beforeRender',
            //'Controller.beforeRedirect' => 'beforeRedirect',
            //'Controller.shutdown' => 'shutdown',
            //'Backend.Controller.buildIndexActions' => [ 'callable' => 'buildIndexActions', 'priority' => 4 ],
            //'Backend.Controller.buildEntityActions' => [ 'callable' => 'buildEntityActions', 'priority' => 4 ]
        ];
    }
}
