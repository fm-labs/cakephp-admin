<?php

namespace Backend;


use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

class BackendPlugin implements EventListenerInterface
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
            'Settings.get' => 'getSettings',
            'Backend.Menu.get' => ['callable' => 'getBackendMenu', 'priority' => 99 ]
        ];
    }

    public function getSettings(Event $event)
    {
        $event->result['Backend'] = [
            'Dashboard.title' => [
                'type' => 'string',
                'input' => [
                    'type' => 'text',
                    'placeholder' => 'Foo'
                ],
                'default' => 'Backend'
            ],
            'Security.enabled' => [
                'type' => 'boolean',
                'inputType' => null,
                'input' => [
                    'placeholder' => 'Foo'
                ],
                'default' => 'Backend'
            ],
            'Site.description' => [
                'type' => 'text',
                'inputType' => null,
                'input' => [
                ],
                'default' => 'Backend'
            ],
            'Site.html' => [
                'type' => 'text',
                'inputType' => 'htmleditor',
                'input' => [
                ],
                'default' => 'Backend'
            ],
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