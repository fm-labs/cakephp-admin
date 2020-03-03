<?php

namespace Backend\Controller\Component;

use Backend\Action\ActionRegistry;
use Backend\Action\ExternalEntityAction;
use Backend\Action\InlineEntityAction;
use Backend\Action\Interfaces\ActionInterface;
use Backend\Action\Interfaces\EntityActionInterface;
use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Response;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

/**
 * Class ActionComponent
 *
 * @package Backend\Controller\Component
 * @TODO Replace ActionRegistry with dynamic initialization of Action objects (load on-the-fly instead of preloading all available actions)
 * @TODO Add method to easily attach event listeners to the Action object
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
     * @var array Map of executed actions
     */
    protected $_executed = [];

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

        // normalize action configs and add actions to actions registry
        $actions = (isset($this->_controller->actions)) ? $this->_controller->actions : [];
        foreach ($actions as $action => $actionConfig) {
            $this->_addAction($action, $actionConfig);
        }
    }

    /**
     * @param string $action Action name
     * @param array $actionConfig Action config
     * @return void
     */
    protected function _addAction($action, $actionConfig = [])
    {
        if ($actionConfig === false) {
            return;
        }
        if (is_string($actionConfig)) {
            $actionConfig = ['className' => $actionConfig];
        }
        $actionConfig = array_merge([
            'className' => null,
            'label' => Inflector::humanize($action),
            'attrs' => [],
            'scope' => [],
            'type' => null,
        ], $actionConfig);

        if ($actionConfig['type'] === null) {
            $className = App::className($actionConfig['className'], 'Action', 'Action');
            $reflection = new \ReflectionClass($className);
            $ifaces = $reflection->getInterfaceNames();
            $actionConfig['type'] = (in_array('Backend\\Action\\Interfaces\\EntityActionInterface', $ifaces)) ? 'entity' : 'table';
        }

        $this->actions[$action] = $actionConfig;
        //$this->_actionRegistry->load($action, $actionConfig);
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
        $config = ['className' => $instance, 'type' => 'entity'] + $options;
        $this->_addAction($action, $config);
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
        $config = ['className' => $instance, 'type' => 'entity'];
        $this->_addAction($action, $config);
    }

    /**
     * @param null|string $action Action name
     * @return null|ActionInterface|object
     */
    public function getAction($action)
    {
        if (!$this->_actionRegistry->has($action)) {
            if (!array_key_exists($action, $this->actions)) {
                throw new NotFoundException("Action not found");
            }
            $this->_actionRegistry->load($action, $this->actions[$action]);
        }

        return $this->_actionRegistry->get($action);
    }

    /**
     * @param null|string $action Action name
     * @return bool
     * @deprecated
     */
    public function hasAction($action)
    {
        return $this->_actionRegistry->has($action);
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
     * @return array
     */
    public function listActions()
    {
        return array_keys($this->actions);
    }

    /**
     * @param null|string $action Action name
     * @return null|\Cake\Network\Response
     */
    public function execute($action = null)
    {
        // Fallback to request action param, if not defined
        if ($action === null) {
            $action = $this->request->params['action'];
        }

        // Prevent double execution (auto and manual)
        if (isset($this->_executed[$action]) && $this->_executed[$action] === true) {
            return null;
        }

        // Get Action class instance
        $this->_action = $this->getAction($action);

        // Init primary model
        $this->model();

        // Attach Action instance to controllers event manager
        if ($this->_action instanceof EventListenerInterface) {
            $this->_controller->eventManager()->on($this->_action);
        }

        // Dispatch 'beforeAction' Event
        //@TODO Rename to 'Backend.Controller.beforeAction'
        $event = $this->_controller->dispatchEvent('Backend.beforeAction', [ 'name' => $action, 'action' => $this->_action ]);
        if ($event->result instanceof Response) {
            return $event->result;
        }

        // Execute the action in context of current controller
        $this->_executed[$action] = true;
        $response = $this->_action->execute($this->_controller);
        if ($response instanceof Response) {
            return $response;
        }

        // Dispatch 'afterAction' Event
        //@TODO Rename to 'Backend.Controller.afterAction'
        $event = $this->_controller->dispatchEvent('Backend.afterAction', [ 'name' => $action, 'action' => $this->_action ]);
        if ($event->result instanceof Response) {
            return $event->result;
        }

    }

    /**
     * @param Event $event The controller event
     * @return null|\Cake\Network\Response
     */
    public function beforeRender(Event $event)
    {
        if (isset($this->request->params['action'])) {
            $action = $this->request->params['action'];
            if (isset($this->actions[$action])) {
                /* @var Controller $controller */
                $controller = $event->getSubject();

                // Inject template and layout via controller view vars
                //$template = (isset($controller->viewVars['template'])) ? $controller->viewVars['template'] : $controller->viewBuilder()->template();
                //$layout = (isset($controller->viewVars['layout'])) ? $controller->viewVars['layout'] : $controller->viewBuilder()->layout();

                // Check if a custom Action class template in the controller's template path,
                // otherwise use the default Action class template.
                // Default Action class template path: {plugin}/src/Template/{prefix}/Action/{action}.ctp
                // Custom Action class template path: {plugin}/src/Template/{prefix}/{controller}/{action}.ctp
                //@TODO Check all application template paths, not only the first configured
                $templateFile = sprintf(
                    "%ssrc/Template/%s/%s.ctp",
                    (isset($this->request->params['plugin'])) ? Plugin::path($this->request->params['plugin']) : App::path('Template')[0],
                    $controller->viewBuilder()->templatePath(),
                    Inflector::underscore($action)
                );
                if (!file_exists($templateFile)) {
                    // build action template path
                    $templatePath = 'Action';
                    if ($this->request->params['prefix']) {
                        $templatePath = Inflector::camelize($this->request->params['prefix']) . '/' . $templatePath;
                    }
                    $controller->viewBuilder()->templatePath($templatePath);

                    // use action class name as default template name
                    $template = ($this->_action->template) ?? null;
                    if (!$template) {
                        $config = $this->actions[$action];
                        list($plugin, $actionClass) = pluginSplit($config['className']);
                        $actionClass = Inflector::underscore($actionClass);
                        $template = ($plugin) ? $plugin . '.' . $actionClass : $actionClass;
                    }

                    $controller->viewBuilder()->template($template);
                }

                // Auto-execute Action class
                // The Action class might have already been executed manually
                //@TODO Make auto-execution of Action classes optional
                $response = $this->execute($action);
                if ($response instanceof Response) {
                    return $response;
                }
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

            $this->_model = TableRegistry::getTableLocator()->get($modelClass);
        }

        return $this->_model;
    }

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Controller.startup' => 'startup',
            'Controller.beforeRender' => 'beforeRender',
        ];
    }
}
