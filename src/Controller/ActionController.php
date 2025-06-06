<?php
declare(strict_types=1);

namespace Admin\Controller;

use Admin\Action\Interfaces\ActionInterface;
use Cake\Controller\Controller;
use Cake\Controller\Exception\MissingActionException;
use Cake\Event\EventInterface;
use Cake\Http\Response;
use Closure;
use LogicException;

class ActionController extends Controller
{
    public $controller;

    public $action;

    public bool $autoRender = true;

    /**
     * @param \Cake\Controller\Controller $controller The parent controller instance
     * @param \Admin\Action\Interfaces\ActionInterface $action The action class instance
     */
    public function __construct(Controller $controller, ActionInterface $action)
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->setEventManager($this->controller->getEventManager());
        //$this->components($this->controller->components());
        //$this->components()->setController($this->controller);
        $this->setRequest($this->controller->getRequest());
        $this->setResponse($this->controller->getResponse());
    }

    /**
     * @inheritDoc
     */
    public function invokeAction(Closure $action, array $args): void
    {
        $request = $this->request;
        if (!isset($request)) {
            throw new LogicException('No Request object configured. Cannot invoke action');
        }
        if (!$this->isAction($request->getParam('action'))) {
            throw new MissingActionException([
                'controller' => $this->name . 'Controller',
                'action' => $request->getParam('action'),
                'prefix' => $request->getParam('prefix'),
                'plugin' => $request->getParam('plugin'),
            ]);
        }

        $this->controller->Action->execute($request->getParam('action'));
    }

    public function isAction($action): bool
    {
        return $this->controller->Action->hasAction($action);
    }

    /**
     * @inheritDoc
     */
    public function __get($key): mixed
    {
        return $this->controller->{$key};
    }

    /**
     * @inheritDoc
     */
    public function __set($key, $val): void
    {
        $this->controller->{$key} = $val;
    }

    /**
     * Magic call wrapper
     *
     * @param string $method Method name
     * @param array $args Method args
     * @return mixed
     */
    //public function __call($method, $args) {
    //    return call_user_func_array([$this->controller, $method], $args);
    //}

    /**
     * @inheritDoc
     */
    public function render($view = null, $layout = null): Response
    {
        $response = $this->controller->render($view, $layout);
        $this->setResponse($response);

        return $response;
    }

    /**
     * @inheritDoc
     */
    public function ddispatchEvent(string $name, ?array $data = null, ?object $subject = null): EventInterface
    {
        if ($subject === null) {
            $subject = $this->controller;
        }

        return parent::dispatchEvent($name, $data, $subject);
    }
}
