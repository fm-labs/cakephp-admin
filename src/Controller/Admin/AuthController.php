<?php
namespace Backend\Controller\Admin;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;
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
        if ($this->components()->has('RequestHandler') && $this->components()->get('RequestHandler')->accepts('json')) {
            $this->viewBuilder()->className('Json');

            $this->Auth->login();

        } else {

            $redirect = $this->Auth->login();
            if ($redirect) {
                $this->redirect($redirect);
            }
        }

        $this->set('login', [
            'user' => $this->Auth->user()
        ]);
        $this->set('_serialize', ['login']);
    }

    /**
     * Login success method
     */
    public function loginSuccess()
    {
        $this->redirect(['_name' => 'backend:admin:dashboard']);
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
