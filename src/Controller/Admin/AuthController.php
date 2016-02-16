<?php
namespace Backend\Controller\Admin;

use Cake\Core\Configure;
use Cake\Event\Event;
use User\Controller\Component\AuthComponent;

/**
 * Class AuthController
 * @package Backend\Controller\Admin
 * @property AuthComponent $Auth
 */
class AuthController extends AppController
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
        if ($this->Auth->user()) {
            $this->Flash->success(__('You are already logged in'));
            $this->redirect(['action' => 'user']);
            return;
        }

        $this->Auth->login();
    }

    /**
     * Login success method
     */
    public function loginSuccess()
    {
        $this->redirect(Configure::read('Backend.Dashboard.url'));
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
