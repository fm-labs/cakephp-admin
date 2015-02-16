<?php

namespace Backend\Controller\Admin;

use Backend\Controller\AppController;
use Backend\Model\Entity\BackendUser;
use Cake\Controller\Component\AuthComponent;

/**
 * Class AuthController
 * @package Backend\Controller
 *
 * @param AuthComponent $Auth
 */
class AuthController extends AppController
{
    public $layout = 'Backend.auth';

    /**
     * Backend login method
     */
    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Auth->flash(__('Login failed'));
            }
        }
    }

    /**
     * Backend logout method
     */
    public function logout()
    {
        $this->Flash->success('You are now logged out!');
        $this->redirect($this->Auth->logout());
    }

    /**
     * Currently logged in user
     */
    public function user()
    {

    }
}
