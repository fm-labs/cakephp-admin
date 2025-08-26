<?php
declare(strict_types=1);

namespace Admin\Controller\Component;

use Admin\Action\ActionRegistry;
use Admin\Action\BaseAction;
use Admin\Action\ExternalEntityAction;
use Admin\Action\InlineEntityAction;
use Admin\Action\Interfaces\ActionInterface;
use Admin\Action\Interfaces\EntityActionInterface;
use Cake\Controller\Component;
use Cake\Controller\Exception\MissingActionException;
use Cake\Event\EventInterface;
use Cake\Http\Response;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use Exception;

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
    public array $actions = [];

    /**
     * @var \Admin\Action\ActionRegistry
     */
    protected ActionRegistry $_actionRegistry;

//    /**
//     * @var \Cake\Controller\Controller Active controller
//     */
//    protected $_controller;

    /**
     * @var \Admin\Action\Interfaces\EntityActionInterface|object Active action
     */
    protected EntityActionInterface $_action;

    /**
     * @var \Cake\ORM\Table Active primary table
     * @deprecated
     */
    protected Table $_model;

    /**
     * @var array Map of executed actions
     */
    protected array $_executed = [];

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
    protected function _addAction(string $action, array $actionConfig = [])
    {
        $actionConfig += ['className' => null, '_action' => $action];
        $this->_actionRegistry->load($action, $actionConfig);
    }

    /**
     * Startup event handler
     *
     * @return \Cake\Http\Response
     */
    public function startup(EventInterface $event)
    {
//        if (Configure::read('debug') && !$this->_actionRegistry->has('debug')) {
//            $actionConfig = ['className' => 'Admin.Debug'];
//            $this->_actionRegistry->load('debug', $actionConfig);
//
//            $infoAction = ['className' => 'Admin.Info';
//            $this->_actionRegistry->load('info', $infoAction);
//        }

        $request = $this->getController()->getRequest();
        $actionName = $request->getParam('action');
        try {
            // check if the action method is available in controller
            // in not a MissingActionException is thrown
            $controllerAction = $this->getController()->getAction();

//            // check if it is a registered action
//            if ($this->hasAction($actionName)) {
//                $action = $this->getAction($actionName);
//                //debug("Found matching inline action: " . get_class($action) . ":" . $action->getLabel());
//                if (!($action instanceof InlineEntityAction)) {
//                    //debug("WARNING: Local controller action is not instance of InlineEntityAction");
//                }
//            }
        } catch (MissingActionException $ex) {
            //debug("Action not found: " . $ex->getMessage());
            // ... the action method is not defined in controller
            // check if it is a registered action
            //$action = $this->getAction($actionName);

            // ... the action is registered
            if ($this->hasAction($actionName)) {
                //debug("Found matching action: " . $actionName);
                $result = $this->execute($actionName);
                if ($result instanceof Response) {
                    return $result;
                }
                $this->getController()->render();

                return $this->getController()->getResponse();
            }

            $response = $this->getController()->getResponse()
                ->withStatus(404, 'Not found')
                ->withStringBody('Not Found');

            return $response;
        } catch (Exception $ex) {
            die($ex->getMessage() . ' ' . get_class($ex));
        }
    }

    public function registerAction(string $actionName, ActionInterface|string $action) {
        $this->_addAction($actionName, ['className' => $action]);
    }

    /**
     * @param \Admin\Action\InlineEntityAction|string|null $action Action name
     * @param array $options Action options
     * @param callable|null $callable Action callback
     * @return void
     */
    public function registerInline(string|InlineEntityAction|null $action, array $options = [], ?callable $callable = null)
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
     * @param string|ExternalEntityAction $action Action name
     * @param array $options Action options
     * @return void
     */
    public function registerExternal(string|ExternalEntityAction $action, array $options = [])
    {
        if ($action instanceof ExternalEntityAction) {
            $instance = $action;
            $action = $instance->getName();
        } else {
            $instance = new ExternalEntityAction($action, $options);
        }
        $config = ['className' => $instance, 'type' => 'entity'];
        $this->_addAction($action, $config);
    }

    /**
     * @param string $action Action name
     * @return \Admin\Action\BaseAction|null
     */
    public function getAction(string $action): ?BaseAction
    {
        $action = $this->_actionRegistry->get($action);
        $action->setController($this->getController());

        return $action;
    }

    /**
     * @param string $action Action name
     * @return bool
     */
    public function hasAction(string $action): bool
    {
        return $this->_actionRegistry->has($action);
    }

    /**
     * @return array
     */
    public function listActions(): array
    {
        return $this->_actionRegistry->loaded();
    }

//    /**
//     * @param null|string $action Action name
//     * @return array
//     * @deprecated Not in use
//     */
//    public function getActionUrl($action)
//    {
//        $actionObj = $this->getAction($action);
//        if ($actionObj instanceof EntityActionInterface) {
//            return ['action' => $action, ':id'];
//        } else {
//            return ['action' => $action];
//        }
//    }

    /**
     * @param string|null $actionName Action name
     * @return \Cake\Http\Response|null
     */
    public function execute(?string $actionName = null)
    {
        $controller = $this->getController();

        // Fallback to request action param, if not defined
        if ($actionName === null) {
            $actionName = $controller->getRequest()->getParam('action');
        }

        // Prevent double execution (auto and manual)
        if (isset($this->_executed[$actionName])) {
            return null;
        }
        $this->_executed[$actionName] = true;

        // Get Action class instance
        $actionObj = $this->getAction($actionName);

        return $this->dispatch($actionObj);
    }

    public function dispatch(BaseAction $action)
    {
        $controller = $this->getController();
        $actionEventKey = $controller->getName() . '.' . $action->getName();

        // Dispatch 'beforeAction' Event
        $event = $controller->dispatchEvent(
            'Admin.Controller.beforeAction',
            ['name' => $actionEventKey, 'action' => $action],
            $controller,
        );
        if ($event->getResult() instanceof Response) {
            return $event->getResult();
        }

        // Prepare view
        $builder = $controller->viewBuilder();

        //$controller->viewBuilder()->setPlugin($action->getPlugin());
        //$controller->viewBuilder()->setTemplatePath($action->getTemplatePath());
        //$controller->viewBuilder()->setTemplate($action->getTemplate());

        if ($builder->getTemplate() === null) {
            $templatePath = $action->getTemplatePath();
            if ($controller->getRequest()->getParam('prefix')) {
                $templatePath = Inflector::camelize($controller->getRequest()->getParam('prefix'))
                    . '/' . $templatePath;
            }

            $template = $action->getTemplate();
            if (!$template) {
                $template = $action->getPlugin() ? $action->getPlugin() . '.' . $action->getName() : $action->getName();
            }

            //debug("no template" . $action->getPlugin() . "::" . $templatePath . "::" . $template);
            $controller->viewBuilder()->setPlugin($action->getPlugin());
            $controller->viewBuilder()->setTemplatePath($templatePath);
            $controller->viewBuilder()->setTemplate($template);

            if ($action->getTemplate()) {
                $builder->setTemplatePath('');
                $builder->setTemplate($action->getTemplate());
            }
        }

        $controller->viewBuilder()->setVar('_action_plugin', $controller->viewBuilder()->getPlugin());
        $controller->viewBuilder()->setVar('_action_template_path', $controller->viewBuilder()->getTemplatePath());
        $controller->viewBuilder()->setVar('_action_template', $controller->viewBuilder()->getTemplate());

        // Execute the action in context of current controller
        //$actionCallable = \Closure::fromCallable([$action, 'invoke']);
        //$controller->invokeAction($actionCallable);
        $response = $action->execute($controller);
        //if ($response instanceof Response) {
        //    return $response;
        //}

        // Dispatch 'afterAction' Event
        $event = $controller->dispatchEvent(
            'Admin.Controller.afterAction',
            ['name' => $actionEventKey, 'action' => $action, 'response' => $response],
            $controller,
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
    public function beforeRender(EventInterface $event): void
    {
//        if ($this->getController()->getRequest()->getParam('action')) {
//            $action = $this->getController()->getRequest()->getParam('action');
//            if ($this->hasAction($action)) {
//                /** @var \Cake\Controller\Controller $controller */
//                $controller = $event->getSubject();
//                $actionObj = $this->getAction($action);
//
//                $builder = $controller->viewBuilder();
//                if ($builder->getTemplate() === null) {
//                    $templatePath = $actionObj->getTemplatePath();
//                    if ($controller->getRequest()->getParam('prefix')) {
//                        $templatePath = Inflector::camelize($controller->getRequest()->getParam('prefix'))
//                            . '/' . $templatePath;
//                    }
//                    $controller->viewBuilder()->setPlugin($actionObj->getPlugin());
//                    $controller->viewBuilder()->setTemplatePath($templatePath);
//                    $controller->viewBuilder()->setTemplate($actionObj->getTemplate());
//                }
//
//                // Auto-execute Action class
//                // The Action class might have already been executed manually
//                //@TODO Make auto-execution of Action classes optional
//                $response = $this->execute($action);
//                if ($response instanceof Response) {
//                    $event->setResult($response);
//                }
//            }
//        }
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
