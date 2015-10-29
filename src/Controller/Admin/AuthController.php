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
        $this->Auth->allow(['login', 'unauthorized']);

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
        $this->Auth->login();
    }

    /**
     * Logout method
     */
    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }

    /**
     * Unauthorized
     */
    public function unauthorized()
    {
        $this->response->statusCode(403);
    }

    /**
     * Current user
     */
    public function user()
    {
        $user = $this->Auth->user();
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }
}
