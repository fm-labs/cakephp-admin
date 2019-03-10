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
     * @return mixed
     */
    public function execute($action = null)
    {
        if ($action === null) {
            $action = $this->request->params['action'];
        }
        $this->_action = $this->getAction($action);
        $this->model(); // init primary model

        // attach Action instance to controllers event manager
        if ($this->_action instanceof EventListenerInterface) {
            $this->_controller->eventManager()->on($this->_action);
        }

        $event = $this->_controller->dispatchEvent('Backend.beforeAction', [ 'name' => $action, 'action' => $this->_action ]);
        if ($event->result instanceof Response) {
            return $event->result;
        }

        // execute the action in context of current controller
        $response = $this->_action->execute($this->_controller);
        if ($response instanceof Response) {
            return $response;
        }

        $event = $this->_controller->dispatchEvent('Backend.afterAction', [ 'name' => $action, 'action' => $this->_action ]);
        if ($event->result instanceof Response) {
            return $event->result;
        }

        // force rendering to capture template exception and fallback to Action template path
        if (!$response && $this->_controller->autoRender) {
            // action name is default template name
            //$template = $this->_controller->request->param('action');
            $template = null;

            try {
                return $this->_controller->render($template);
                //$eventManager = null; // drop event manager backup
            } catch (MissingTemplateException $ex) {
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
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Controller.startup' => 'startup',
        ];
    }
}
