<?php
namespace Backend\Controller\Admin;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Response;
use User\Controller\Component\AuthComponent;

/**
 * Class AuthController
 * d
 * @package Backend\Controller\Admin
 * @property AuthComponent $Auth
 */
class AuthController extends AppController
{
    /**
     * @param Event $event
     * @return \Cake\Network\Response|null|void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['login', 'unauthorized']);
        $this->viewBuilder()->layout('Backend.auth');
    }

    /**
     * Login method
     *
     * @return null|Response
     */
    public function login()
    {
        if ($this->components()->has('RequestHandler') && $this->components()->get('RequestHandler')->accepts('json')) {
            $this->viewBuilder()->className('Json');
            $this->Auth->login();
        } else {
            $redirect = $this->Auth->login();
            if ($redirect) {
                return $this->redirect($redirect);
            }
        }

        $this->set('login', [
            'user' => $this->Auth->user()
        ]);
        $this->set('_serialize', ['login']);
    }

    /**
     * Login success method
     *
     * @return Response
     */
    public function loginSuccess()
    {
        $redirect = ['_name' => 'backend:admin:dashboard'];
        if (Configure::check('Backend.Dashboard.url')) {
            $redirect = Configure::read('Backend.Dashboard.url');
        }

        return $this->redirect($redirect);
    }

    /**
     * Logout method
     *
     * @return Response
     */
    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }

    /**
     * Unauthorized
     *
     * @return null
     */
    public function unauthorized()
    {
        $this->response->statusCode(403);
    }

    /**
     * Current user
     *
     * @return null
     */
    public function user()
    {
        $user = $this->Auth->user();
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }
}
