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
        //debug($this->components()->loaded());
        //foreach ($this->components()->loaded() as $c) {
        //    debug($c . " -> " . get_class($this->components()->get($c)));
        //}
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
