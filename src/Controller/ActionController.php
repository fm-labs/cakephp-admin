<?php

namespace Backend\Controller;

use Backend\Action\Interfaces\ActionInterface;
use Cake\Controller\Controller;
use Cake\Event\Event;

class ActionController extends Controller
{
    public $controller;

    public $action;

    /**
     * @param Controller $controller The parent controller instance
     * @param ActionInterface $action The action class instance
     */
    public function __construct(Controller $controller, ActionInterface $action)
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->eventManager($this->controller->eventManager());
        //$this->components($this->controller->components());
        //$this->components()->setController($this->controller);
        $this->setRequest($this->controller->request);
        $this->response = $this->controller->response;
    }

    /**
     * {@inheritDoc}
     */
    public function invokeAction()
    {
        return $this->Action->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function __get($key)
    {
        return $this->controller->{$key};
    }

    /**
     * {@inheritDoc}
     */
    public function __set($key, $val)
    {
        return $this->controller->{$key} = $val;
    }

    /**
     * Magic call wrapper
     *
     * @param string $method Method name
     * @param array $args Method args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->controller, $method], $args);
    }

    /**
     * {@inheritDoc}
     */
    public function render($view = null, $layout = null)
    {
        return $this->controller->render($view, $layout);
    }

    /**
     * {@inheritDoc}
     */
    public function dispatchEvent($name, $data = null, $subject = null)
    {
        if ($subject === null) {
            $subject = $this->controller;
        }

        return parent::dispatchEvent($name, $data, $subject);
    }
}
