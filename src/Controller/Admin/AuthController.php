<?php
declare(strict_types=1);

namespace Backend\Controller\Admin;

use Cake\Core\Configure;

/**
 * Class AuthController
 * d
 * @package Backend\Controller\Admin
 * @property \Backend\Controller\Component\AuthComponent $Auth
 * @property \User\Controller\Component\UserSessionComponent $UserSession
 */
class AuthController extends AppController
{
    public $modelClass = false;

    /**
     * @param \Cake\Event\Event $event The event object
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['login', 'unauthorized', 'session']);
        $this->viewBuilder()->setLayout('Backend.auth');
    }

    /**
     * Login method
     *
     * @return null|\Cake\Http\Response
     */
    public function login()
    {
        $user = $this->Auth->login();
        if ($this->components()->has('RequestHandler') && $this->components()->get('RequestHandler')->accepts('json')) {
            $this->viewBuilder()->setClassName('Json');
        } elseif ($user) {
            return $this->redirect($this->Auth->redirectUrl());
        }

        $this->set('login', [
            'user' => $user ? $user['id'] : null,
        ]);
        $this->set('_serialize', ['login']);

        return null;
    }

    /**
     * Login success method
     *
     * @return \Cake\Http\Response
     */
    public function loginSuccess()
    {
        $redirect = ['_name' => 'admin:backend:dashboard'];
        if (Configure::check('Backend.Dashboard.url')) {
            $redirect = Configure::read('Backend.Dashboard.url');
        }

        return $this->redirect($redirect);
    }

    /**
     * Logout method
     *
     * @return \Cake\Http\Response
     */
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    /**
     * Unauthorized
     *
     * @return void
     */
    public function unauthorized()
    {
        $this->setResponse($this->getResponse()->withStatus(403));
    }

    /**
     * Current user
     *
     * @return void
     */
    public function user()
    {
        $user = $this->Auth->user();
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Return client session info in JSON format
     *
     * @return void
     */
    public function session()
    {
        $this->viewBuilder()->setClassName('Json');
        $data = $this->UserSession->extractSessionInfo();
        $this->set('data', $data);
        $this->set('_serialize', 'data');
    }
}
