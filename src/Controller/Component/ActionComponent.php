<?php

namespace Backend\Controller\Component;

use Backend\Action\ActionRegistry;
use Backend\Action\ExternalEntityAction;
use Backend\Action\InlineEntityAction;
use Backend\Action\Interfaces\ActionInterface;
use Backend\Action\Interfaces\EntityActionInterface;
use Backend\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Network\Exception\NotFoundException;
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
     * @var string Active action name
     */
    protected $_actionName;

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
     *
     * @param array $config Component configuration
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

    /**
     * Startup event handler
     *
     * @return void
     */
    public function startup()
    {
        if (Configure::read('debug') && !isset($this->actions['debug'])) {
            //$actionConfig = ['className' => 'Backend.Debug'];
            //$this->_actionRegistry->load('debug', $actionConfig);
            //$this->actions['debug'] = $actionConfig;

            /*
            $infoAction = ['className' => 'Backend.View', 'label' => 'Info'];
            $this->_actionRegistry->load('info', $infoAction);
            $this->actions['info'] = $infoAction;
            */
        }
    }

    /**
     * @param null|string $action Action name
     * @param array $options Action options
     * @param null|callable $callable Action callback
     * @return void
     */
    public function registerInline($action, array $options = [], $callable = null)
    {
        if ($action instanceof InlineEntityAction) {
            $instance = $action;
            $action = $instance->action;
        } else {
            $options += ['action' => $action];
            $instance = new InlineEntityAction($this->_controller, $options, $callable);
        }
        if (isset($options['filter'])) {
            $instance->setFilter($options['filter']);
        }
        $config = ['className' => $instance];
        $this->_actionRegistry->load($action, $config);
        $this->actions[$action] = [];
    }

    /**
     * @param null|string $action Action name
     * @param array $options Action options
     * @return void
     */
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
     * @param null|string $action Action name
     * @return bool
     */
    public function hasAction($action)
    {
        return $this->_actionRegistry->has($action);
    }

    /**
     * @param null|string $action Action name
     * @return null|ActionInterface|object
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
        return array_keys($this->actions);
    }

    /**
     * @param null|string $action Action name
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
     * @param null|string $action Action name
     * @return mixed
     */
    public function execute($action = null)
    {
        if ($action === null) {
            $action = $this->request->params['action'];
        }
        //debug("execute: $action");

        if (!array_key_exists($action, $this->actions)) {
            throw new NotFoundException("Action not found");
        }

        // prevent recursive action calls
        if ($action == $this->_actionName) {
            return null;
        }

//        // prevent recursive action calls (2)
//        if ($this->_executing == true) {
//            debug("already executing " . $this->_actionName);
//
//            return null;
//        }
//        $this->_executing = true;

        if (!$this->_actionRegistry->has($action)) {
            $this->_actionRegistry->load($action, $this->actions[$action]);
        }

        $this->_actionName = $action;
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

        // execute the action in context of current controller
        $response = $this->_action->execute($this->_controller);
        //$this->_executed = true;
        //debug("executed!");

        $event = $this->_registry->getController()->dispatchEvent('Backend.afterAction', [ 'name' => $action, 'action' => $this->_action ]);
        if ($event->result instanceof Response) {
            return $event->result;
        }

        // force rendering to capture template exception and fallback to Action template path
        if (!$response && $this->_controller->autoRender) {
            //debug("unrendered!");

            // create a clone of the controller eventManager
            // we need this as a workaround, when the MissingTemplateException is thrown,
            // to prevent view events to get triggered twice.
            //$eventManager = clone $this->_controller->eventManager();

            // action name is default template name
            //$template = $this->_controller->request->param('action');
            $template = null;

            try {
                return $this->_controller->render($template);
                //$eventManager = null; // drop event manager backup
            } catch (MissingTemplateException $ex) {
                // restore event manager from backup clone
                //$this->_controller->eventManager($eventManager);

                // build action template path
                $templatePath = 'Action';
                if ($this->request->params['prefix']) {
                    $templatePath = Inflector::camelize($this->request->params['prefix']) . '/' . $templatePath;
                }

                // use action class name as default template name
                $template = ($this->_action->template) ?? null;
                if (!$template) {
                    $config = $this->actions[$action];
                    list($plugin, $actionClass) = pluginSplit($config['className']);
                    $actionClass = Inflector::underscore($actionClass);
                    $template = ($plugin) ? $plugin . '.' . $actionClass : $actionClass;
                }

                //$this->_controller->viewBuilder()->templatePath($templatePath);
                //$this->_controller->viewBuilder()->template($template);
                //$response = $this->_controller->render();

                // After Controller::render() has been triggered, the $View property
                // is available
                $this->_controller->View->templatePath($templatePath);
                $this->_controller->View->template($template);
                $this->response->body($this->_controller->View->render());

                return $this->response;
            }
        }
    }

    /**
     * @return \Cake\ORM\Table
     */
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

    /**
     * @param Event $event The event object
     * @return void
     */
    public function beforeRender(Event $event)
    {
        $controller = $event->subject();

        // actions
        //@todo Move to ActionToolbar component
        $actions = (isset($controller->viewVars['toolbar.actions'])) ? $controller->viewVars['toolbar.actions'] : [];
        if ($this->_action) {
            if ($this->_action instanceof EntityActionInterface) {
                $entity = $this->_action->entity();

                // first add the primary action
                /*
                try {
                    $actions['primary'] = $this->_buildToolbarAction('view', $this->getAction('view'), $entity);
                } catch (\Exception $ex) {
                    debug($ex->getMessage());
                }
                */

                foreach ($this->listActions() as $actionName) {
                    /*
                    if ($actionName == 'view') {
                        continue;
                    }
                    */

                    $actionObj = $this->getAction($actionName);
                    if ($actionObj == $this->_action) {
                        continue;
                    }

                    /*
                    if ($action == "index") {
                        $actions[$action] = [$_action->getLabel(), ['action' => $action], $_action->getAttributes()];
                    }
                    else*/
                    if ($actionObj instanceof EntityActionInterface && in_array('form', $actionObj->getScope()) && $actionObj->isUsable($entity)) {
                        $actions[$actionName] = $this->_buildToolbarAction($actionName, $actionObj, $entity);
                    }
                }
            } elseif ($this->_action instanceof IndexActionInterface) {
                foreach ($this->listActions() as $actionName) {
                    $actionObj = $this->getAction($actionName);
                    if ($actionObj == $this->_action) {
                        continue;
                    }
                    if (!in_array('index', $actionObj->getScope())) {
                        continue;
                    }
                    $actions[$actionName] = $this->_buildToolbarAction($actionName, $actionObj, null);
                }
            }
        }

        //$controller->set('toolbar.actions', array_reverse($actions));
        $controller->set('toolbar.actions', $actions);
    }

    /**
     * @param Event $event The event object
     * @return void
     * @deprecated
     */
    public function buildIndexActions(Event $event)
    {
        foreach ($this->listActions() as $action) {
            $_action = $this->getAction($action);
            if ($_action == $this->_action) {
                continue;
            }
            if ($action == "index" || (!($_action instanceof IndexActionInterface))) {
                continue;
            }
            $event->data['actions'][$action] = [$_action->getLabel(), ['action' => $action], $_action->getAttributes()];
        }
    }

    /**
     * @param Event $event The event object
     * @return void
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
            } elseif ($_action instanceof EntityActionInterface) {
                $event->data['actions'][$action] = [$_action->getLabel(), ['action' => $action, ':id'], $_action->getAttributes()];
            }
        }

        if (method_exists($event->subject(), 'buildEntityActions')) {
            call_user_func([$event->subject(), 'buildEntityActions'], $event);
        }
    }

    /**
     * @param string $alias Action alias
     * @param ActionInterface $action The action instance
     * @param EntityInterface $entity The entity instance
     * @return array
     */
    protected function _buildToolbarAction($alias, ActionInterface $action, EntityInterface $entity = null)
    {
        if ($entity === null) {
            return [$action->getLabel(), ['action' => $alias], $action->getAttributes()];
        }

        return [$action->getLabel(), ['action' => $alias, $entity->id], $action->getAttributes()];
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
