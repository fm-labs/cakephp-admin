<?php
declare(strict_types=1);

namespace Admin\Service;

use Cake\Event\Event;
use Cake\Log\Log;

class ControllerDebugService
{
    public function implementedEvents(): array
    {
        return [
            'Controller.initialize' => 'beforeFilter',
            'Controller.startup' => 'startup',
            'Controller.beforeRender' => 'beforeRender',
            'Controller.beforeRedirect' => 'beforeRedirect',
            'Controller.shutdown' => 'shutdown',
        ];
    }

    public function beforeFilter(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }

    public function startup(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }

    public function beforeRender(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }

    public function beforeRedirect(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }

    public function shutdown(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }

    protected function _logEvent($eventName, Event $event)
    {
        Log::debug(sprintf('ControllerDebug [%s:%s] %s %s',
            static::class, $eventName, $event->getName(), get_class($event->getSubject())));
    }
}
