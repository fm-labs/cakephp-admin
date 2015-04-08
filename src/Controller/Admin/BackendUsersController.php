<?php
namespace Backend\Controller\Admin;

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * BackendUsers Controller
 *
 * @property \User\Model\Table\UsersTable $Users
 */
class BackendUsersController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        if (!Configure::check('Backend.userModel')) {
            throw new Exception('Backend: User model not configured');
        }
        $this->Users = TableRegistry::get(Configure::read('Backend.userModel'));
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('users', $this->paginate($this->Users));
        $this->set('_serialize', ['users']);
    }

}
