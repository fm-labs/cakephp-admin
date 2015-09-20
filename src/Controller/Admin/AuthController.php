<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/25/15
 * Time: 8:07 PM
 */

namespace Backend\Controller\Admin;

use Backend\Controller\Admin\AbstractBackendController;
use Cake\Event\Event;

class AuthController extends AbstractBackendController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['login']);

        $this->viewBuilder()->layout("Backend.auth");
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
    }

    /**
     * Login method
     */
    public function login()
    {
        $this->Auth->userLogin();
    }

    /**
     * Logout method
     */
    public function logout()
    {
        $this->Auth->userLogout();
    }
}
