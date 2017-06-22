<?php

namespace Backend\Controller\Component;

use Cake\Event\Event;

/**
 * Class AuthComponent
 *
 * @package Backend\Controller\Component
 */
class AuthComponent extends \User\Controller\Component\AuthComponent
{

    /**
     * @param array $config
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        // default login action
        if (!$this->config('loginAction')) {
            $this->config('loginAction', ['plugin' => 'User', 'controller' => 'User', 'action' => 'login']);
        }

        // default authenticate
        if (!$this->config('authenticate')) {
            $this->config('authenticate', [
                self::ALL => ['userModel' => $this->config('userModel'), 'finder' => 'authUser'],
                'Form' => ['userModel' => $this->config('userModel')]
            ]);
        }

        // default authorize
        if (!$this->config('authorize')) {
            //$this->config('authorize', [
            //    'Controller'
            //]);
        }

        $this->Users = $this->_registry->getController()->loadModel($this->config('userModel'));
    }

    /**
     * Login method
     *
     * @return string|array Redirect url
     * @todo DRY! Duplicates code from the User's plugin AuthComponent
     */
    public function login()
    {
        // check if user is already authenticated
        if ($this->user()) {
            return $this->redirectUrl();
        }

        // attempt to identify user (any request method)
        $user = $this->identify();
        if ($user) {
            // dispatch 'User.login' event
            $event = new Event('Backend.User.login', $this, [
                'user' => $user,
                'request' => $this->request
            ]);
            $this->eventManager()->dispatch($event);

            // authenticate user
            $this->setUser($event->data['user']);

            // redirect to originally requested url (or login redirect url)
            return $this->redirectUrl();

            // form login obviously failed
        } elseif ($this->request->is('post')) {
            $this->flash(__d('user', 'Login failed'));

            // dispatch 'User.login' event
            $event = new Event('Backend.User.loginFailed', $this, [
                'request' => $this->request
            ]);
            $this->eventManager()->dispatch($event);

            // all other authentication providers also failed to authenticate
            // or no further authentication has occured
        } else {
            // show login form
        }
    }
}
