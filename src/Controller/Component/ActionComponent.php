<?php
declare(strict_types=1);

namespace Admin\Controller\Component;

use Admin\Action\ActionRegistry;
use Admin\Action\ExternalEntityAction;
use Admin\Action\InlineEntityAction;
use Admin\Action\Interfaces\EntityActionInterface;
use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

/**
 * Class ActionComponent
 *
 * @package Admin\Controller\Component
 */
class ActionComponent extends Component
{
    /**
     * @var array List of configured actions
     */
    public $actions = [];

    /**
     * @var \Admin\Action\ActionRegistry
     */
    protected $_actionRegistry;

    /**
     * @var \Cake\Controller\Controller Active controller
     */
    protected $_controller;

    /**
     * @var \Admin\Action\Interfaces\EntityActionInterface|object Active action
     */
    protected $_action;

    /**
     * @var \Cake\ORM\Table Active primary table
     * @deprecated
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
    public function initialize(array $config): void
    {
        $this->_actionRegistry = new ActionRegistry();

        // normalize action configs and add actions to actions registry
        $actions = $this->getController() && isset($this->getController()->actions)
            ? $this->getController()->actions : [];
        foreach ($actions as $action => $actionConfig) {
            if (isset($actionConfig['inline'])) {
                $this->registerInline($action, $actionConfig);
                continue;
            }
            if (is_string($actionConfig)) {
                $actionConfig = ['className' => $actionConfig];
            }
            $this->_addAction($action, $actionConfig);
        }
    }

    /**
     * @return \Admin\Action\ActionRegistry
     */
    public function getActionRegistry(): ActionRegistry
    {
        return $this->_actionRegistry;
    }

    /**
     * @param string $action Action name
     * @param array $actionConfig Action config
     * @return void
     */
    protected function _addAction($action, array $actionConfig = [])
    {
        $actionConfig += ['className' => null];
        $this->_actionRegistry->load($action, $actionConfig);
    }

    /**
     * Startup event handler
     *
     * @return void
     */
    public function startup()
    {
//        if (Configure::read('debug') && !$this->_actionRegistry->has('debug')) {
//            $actionConfig = ['className' => 'Admin.Debug'];
//            $this->_actionRegistry->load('debug', $actionConfig);
//
//            $infoAction = ['className' => 'Admin.Info';
//            $this->_actionRegistry->load('info', $infoAction);
//        }
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
            $instance = new InlineEntityAction($this->getController(), $options, $callable);
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
     * @return null|\Admin\Action\Interfaces\ActionInterface|object
     */
    public function getAction($action)
    {
        return $this->_actionRegistry->get($action);
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
     * @return array
     */
    public function listActions()
    {
        return $this->_actionRegistry->loaded();
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
     * @return null|\Cake\Http\Response
     */
    public function execute($action = null)
    {
        // Fallback to request action param, if not defined
        if ($action === null) {
            $action = $this->getController()->getRequest()->getParam('action');
        }

        // Prevent double execution (auto and manual)
        if (isset($this->_executed[$action])) {
            return null;
        }

        // Get Action class instance
        $this->_action = $this->getAction($action);

        // Dispatch 'beforeAction' Event
        $event = $this->getController()->dispatchEvent(
            'Admin.Controller.beforeAction',
            [ 'name' => $action, 'action' => $this->_action ]
        );
        if ($event->getResult() instanceof Response) {
            return $event->getResult();
        }

        // Execute the action in context of current controller
        $this->_executed[$action] = true;
        $response = $this->_action->execute($this->getController());
        //if ($response instanceof Response) {
        //    return $response;
        //}

        // Dispatch 'afterAction' Event
        $event = $this->getController()->dispatchEvent(
            'Admin.Controller.afterAction',
            [ 'name' => $action, 'action' => $this->_action, 'response' => $response]
        );
        if ($event->getResult() instanceof Response) {
            return $event->getResult();
        }

        return $response;
    }

    /**
     * @param \Cake\Event\EventInterface $event The controller event
     * @return void
     */
    public function beforeRender(\Cake\Event\EventInterface $event): void
    {
        if ($this->getController()->getRequest()->getParam('action')) {
            $action = $this->getController()->getRequest()->getParam('action');
            if ($this->hasAction($action)) {
                /** @var \Cake\Controller\Controller $controller */
                $controller = $event->getSubject();
                $actionObj = $this->getAction($action);

                $builder = $controller->viewBuilder();
                if ($builder->getTemplate() === null) {
                    $templatePath = $actionObj->getTemplatePath();
                    if ($controller->getRequest()->getParam('prefix')) {
                        $templatePath = Inflector::camelize($controller->getRequest()->getParam('prefix'))
                            . '/' . $templatePath;
                    }
                    $controller->viewBuilder()->setPlugin($actionObj->getPlugin());
                    $controller->viewBuilder()->setTemplatePath($templatePath);
                    $controller->viewBuilder()->setTemplate($actionObj->getTemplate());
                }

                // Auto-execute Action class
                // The Action class might have already been executed manually
                //@TODO Make auto-execution of Action classes optional
                $response = $this->execute($action);
                if ($response instanceof Response) {
                    $event->setResult($response);
                }
            }
        }
    }

    /**
     * @return array
     */
    public function implementedEvents(): array
    {
        return [
            'Controller.startup' => 'startup',
            'Controller.beforeRender' => 'beforeRender',
        ];
    }
}
