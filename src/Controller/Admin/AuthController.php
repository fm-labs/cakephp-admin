<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

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

        if ($this->Authentication) {
            $this->Authentication->allowUnauthenticated(['login', 'logout', 'unauthorized', 'session']);
        }

        //if (Configure::read('Admin.Auth.authorizationEnabled')) {
        //    $this->Authorization->setConfig([
        //        'skipAuthorization' => ['login', 'logout', 'unauthorized', 'session'],
        //    ]);
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
     * User profile view
     *
     * @return null|\Cake\Http\Response
     */
    public function user(): ?\Cake\Http\Response
    {
        $user = $this->getRequest()->getAttribute('adminIdentity');
        $this->set('user', $user);
        $this->viewBuilder()->setOption('serialize', ['user']);

        //@todo Remove
        if (!$user) {
            return $this->render('user_noauth');
        }

        return null;
    }

    /**
     * Login method
     *
     * @return null|\Cake\Http\Response|void
     * @throws \Exception
     */
    public function login()
    {
        /*
        $user = $this->Authentication->getIdentity();
        if ($user) {}
        */

        $result = $this->Authentication->getResult();
        // login successful
        if ($result->isValid()) {
            $redirectUrl = $this->Authentication->getLoginRedirect();
            $redirectUrl = $redirectUrl ?: ['_name' => 'admin:index'];
            $this->Flash->success(__d('admin', 'You are logged in now'), ['key' => 'auth']);

            return $this->redirect($redirectUrl);
        }

        // login failed (via POST)
        if ($this->request->is('post') && !$result->isValid()) {
            $this->dispatchEvent('User.Auth.error', ['scope' => 'Admin'], $this);
            $this->Flash->error(__d('admin', 'Invalid login credentials'), ['key' => 'auth']);
        }
    }

    /**
     * Logout method
     *
     * @return \Cake\Http\Response
     */
    public function logout()
    {
        $redirect = $this->Authentication->logout();
        $redirect = $redirect ?: ['_name' => 'admin:auth:user:login'];

        return $this->redirect($redirect);
    }

    /**
     * Return client session info in JSON format
     *
     * @return void
     */
    public function session()
    {
        $this->viewBuilder()->setClassName('Json');
        $data = [
            't' => time(),
        ];
        $identity = $this->Authentication->getIdentity();
        if ($identity) {
            $data['id'] = $identity->getIdentifier();
        }
        $this->set('data', $data);
        $this->viewBuilder()->setOption('serialize', 'data');
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
}
