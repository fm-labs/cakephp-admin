<?php
namespace Backend\Controller\Admin;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Response;

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
     * @param Event $event The event object
     * @return \Cake\Network\Response|null|void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['login', 'unauthorized', 'session']);
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
            'user' => $this->Auth->user() //@TODO Only send minimum data!
        ]);
        $this->set('_serialize', ['login']);

        return null;
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
        return $this->redirect($this->Auth->logout());
    }

    /**
     * Unauthorized
     *
     * @return void
     */
    public function unauthorized()
    {
        $this->response->statusCode(403);
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
        $this->viewBuilder()->className('Json');
        $data = $this->UserSession->extractSessionInfo();
        $this->set('data', $data);
        $this->set('_serialize', 'data');
    }
}
