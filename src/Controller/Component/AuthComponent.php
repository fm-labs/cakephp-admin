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
            $this->flash(__d('backend', 'Login failed'));

            // dispatch 'User.login' event
            $event = new Event('Backend.User.loginFailed', $this, [
                'user' => false,
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
