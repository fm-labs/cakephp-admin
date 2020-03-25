<?php

namespace Backend\Http;

use Backend\Controller\ActionController;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Http\ControllerFactory;

class ActionDispatcherListener implements EventListenerInterface
{
    /**
     * {@inheritDoc}
     */
    public function implementedEvents(): array
    {
        return ['Dispatcher.beforeDispatch' => 'beforeDispatch'];
    }

    /**
     * @param Event $event The event object
     * @return void|\Cake\Http\Response
     */
    public function beforeDispatch(Event $event)
    {
        /* @var $request \Cake\Http\ServerRequest */
        $request = $event->getData('request');
        /* @var $response \Cake\Http\Response */
        $response = $event->getData('response');
        /* @var $dispatcher \Cake\Http\ActionDispatcher */
        $dispatcher = $event->getSubject();

        $controllerFactory = new ControllerFactory();
        $controller = $controllerFactory->create($request, $response);

        if ($controller->components()->has('Action')) {
            //debug("has component");
            $action = $request->getParam('action');
            $prefix = $request->getParam('prefix');

            $actionList = $controller->Action->listActions();
            //debug($actionList);
            if (in_array($action, $actionList)) {
                // check if method is defined in controller
                $reflection = new \ReflectionObject($controller);
                if (!$reflection->hasMethod($action)) {
                    $actionObj = $controller->Action->getAction($action);
                    $controller = new ActionController($controller, $actionObj);
                }
            }
        }

        $event->setData('controller', $controller);
    }
}
