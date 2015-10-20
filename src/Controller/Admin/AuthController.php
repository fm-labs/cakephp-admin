<?php
namespace Backend\Controller\Admin;

use Cake\Event\Event;

/**
 * Class AuthController
 * @package Backend\Controller\Admin
 * @property UserAuthComponent $Auth
 */
class AuthController extends AbstractBackendController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['login']);

        $this->viewBuilder()->layout('Backend.auth');
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
        $this->set('title', __('Login'));
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
