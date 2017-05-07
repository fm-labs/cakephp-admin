<?php

namespace Backend\Controller\Admin;

use Backend\Controller\BackendActionsTrait;
use Cake\Controller\Controller;
use Cake\Event\Event;

class AppController extends Controller
{
    use BackendActionsTrait;

    public function initialize()
    {
        $this->loadComponent('Backend.Backend');
    }

    /*
    public function beforeRender(Event $event)
    {
        $this->response->header("Access-Control-Allow-Headers", "Content-Type");
        $this->response->header("Access-Control-Allow-Origin", "*");
        $this->response->header("Access-Control-Allow-Credentials", "true");
    }
    */
}
