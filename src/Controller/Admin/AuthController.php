<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/25/15
 * Time: 8:07 PM
 */

namespace Backend\Controller\Admin;

use Backend\Controller\Admin\AbstractBackendController;
use Cake\Event\Event;

class AuthController extends AbstractBackendController
{
    /**
     * @var string Name of auth layout
     */
    public $layout = 'Backend.auth';

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        // allow login method to pass authentication
        $this->Auth->allow(['login']);
    }

    /**
     * Login method
     */
    public function login()
    {
        $this->Auth->userLogin();
    }

    /**
     * Logout method
     */
    public function logout()
    {
        $this->Auth->userLogout();
    }
}
