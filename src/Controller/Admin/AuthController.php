<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cake\Core\Configure;

/**
 * Class AuthController
 *
 * @package Admin\Controller\Admin
 */
class AuthController extends AppController
{
    public $modelClass = false;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        //if (Configure::read('Admin.Auth.authenticationEnabled')) {
            $this->Authentication->allowUnauthenticated(['login', 'logout', 'unauthorized', 'session']);
        //}
        //if (Configure::read('Admin.Auth.authorizationEnabled')) {
            $this->Authorization->setConfig([
                'skipAuthorization' => ['login', 'logout', 'unauthorized', 'session'],
            ]);
        //}
    }

    /**
     * @param \Cake\Event\EventInterface $event The event object
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->viewBuilder()->setLayout('Admin.auth');
    }

    /**
     * Login method
     *
     * @return null|\Cake\Http\Response
     * @throws \Exception
     */
    public function login()
    {
        $user = $this->Auth->user();
        if ($user) {
            $this->Flash->info("Already logged in");
            //if ($user->can('access', $this)) {
                // do something
            //}
            //return;
        }

        $user = $this->Auth->login();
        if ($this->components()->has('RequestHandler') && $this->components()->get('RequestHandler')->accepts('json')) {
            $this->viewBuilder()->setClassName('Json');
        } elseif ($user) {
            $redirectUrl = $this->Auth->redirectUrl();
            $redirectUrl = $redirectUrl ?: ['_name' => 'admin:system:dashboard'];

            return $this->redirect($redirectUrl);
        }

        $this->set('login', [
            'user' => $user ? $user['id'] : null,
        ]);
        $this->set('_serialize', ['login']);

        return null;
    }

    /**
     * Logout method
     *
     * @return \Cake\Http\Response
     */
    public function logout()
    {
        $redirect = $this->Authentication->logout();
        $redirect = $redirect ?: ['_name' => 'admin:system:user:login'];
        //@TODO: Fix admin user logout: Authentication identity not cleared, when logging out from admin
        // WORKAROUND: Redirects to user logout to make sure user is logged out
        $redirect = '/user/logout';

        return $this->redirect($redirect);
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
     * @todo Evaluate
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
     * @todo Evaluate
     */
    public function session()
    {
        $this->viewBuilder()->setClassName('Json');
        //$data = $this->UserSession->extractSessionInfo();
        //$data = ['id' => $this->Auth->user('id')];
        $data = [
            't' => time(),
        ];
        $identity = $this->Authentication->getIdentity();
        if ($identity) {
            $data['id'] = $identity->getIdentifier();
        }
        $this->set('data', $data);
        $this->set('_serialize', 'data');
    }
}
