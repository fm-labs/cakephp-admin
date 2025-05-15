<?php
declare(strict_types=1);

namespace Admin\Http;

use Admin\Controller\ActionController;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Http\ControllerFactory;
use Cake\Http\Response;
use ReflectionObject;

/**
 * @internal Experimental Action-aware dispatcher
 * @deprecated Dispatcher is deprecated.
 * @todo Migrate
 */
class ActionDispatcherListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return ['Dispatcher.beforeDispatch' => 'beforeDispatch'];
    }

    /**
     * @param \Cake\Event\Event $event The event object
     * @return \Cake\Http\Response|void
     */
    public function beforeDispatch(Event $event): ?Response
    {
        /** @var \Cake\Http\ServerRequest $request */
        $request = $event->getData('request');
        /** @var \Cake\Http\Response $response */
        $response = $event->getData('response');
        /** @var \Cake\Http\ActionDispatcher $dispatcher */
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
                $reflection = new ReflectionObject($controller);
                if (!$reflection->hasMethod($action)) {
                    $actionObj = $controller->Action->getAction($action);
                    $controller = new ActionController($controller, $actionObj);
                }
            }
        }

        $event->setData('controller', $controller);
    }
}
