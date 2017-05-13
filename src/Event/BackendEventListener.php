<?php

namespace Backend\Event;


use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

class BackendEventListener implements EventListenerInterface
{

    /**
     * Returns a list of events this object is implementing. When the class is registered
     * in an event manager, each individual method will be associated with the respective event.
     *
     * @see EventListenerInterface::implementedEvents()
     * @return array associative array or event key names pointing to the function
     * that should be called in the object when the respective event is fired
     */
    public function implementedEvents()
    {
        return [
            'Backend.Menu.get' => 'getBackendMenu'
        ];
    }

    public function getBackendMenu(Event $event)
    {
        $event->subject()->addItem([
            'title' => 'System',
            'url' => ['plugin' => 'Backend', 'controller' => 'Dashboard', 'action' => 'backend'],
            'data-icon' => 'cube',

            'children' => [
                'system' => [
                    'title' => 'Systeminfo',
                    'url' => ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                    'data-icon' => 'info'
                ],
                'logs' => [
                    'title' => 'Logs',
                    'url' => ['plugin' => 'Backend', 'controller' => 'Logs', 'action' => 'index'],
                    'data-icon' => 'file-text-o',
                ],
                'cache' => [
                    'title' => 'Cache',
                    'url' => ['plugin' => 'Backend', 'controller' => 'Cache', 'action' => 'index'],
                    'data-icon' => 'file-text-o',
                ]
            ]
        ]);
    }
}