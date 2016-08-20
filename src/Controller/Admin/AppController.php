<?php

namespace Backend\Controller\Admin;

use Cake\Event\Event;

class AppController extends AbstractBackendController
{
    public function beforeRender(Event $event)
    {
        //@TODO Move to a CORSComponent
        $this->response->header("Access-Control-Allow-Headers", "Content-Type");
        $this->response->header("Access-Control-Allow-Origin", "*");
        $this->response->header("Access-Control-Allow-Credentials", "true");
    }
}
