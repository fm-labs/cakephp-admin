<?php
namespace Backend\Controller\Admin;

use Backend\Controller\Admin\AppController;

/**
 * Users Controller
 *
 * @property \Backend\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['PrimaryGroup']
        ];
        $this->set('users', $this->paginate($this->Users));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['PrimaryGroup', 'Groups']
        ]);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        $user->accessible([
            'username', 'group_id', 'name', 'email', 'password'
        ], true);
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The {0} has been saved.', __('user')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('user')));
            }
        }
        $primaryGroup = $this->Users->PrimaryGroup->find('list', ['limit' => 200]);
        $userGroups = $this->Users->Groups->find('list', ['limit' => 200]);
        $this->set(compact('user', 'primaryGroup', 'userGroups'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Groups']
        ]);
        $user->accessible('*', true);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The {0} has been saved.', __('user')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('user')));
            }
        }
        $primaryGroup = $this->Users->PrimaryGroup->find('list', ['limit' => 200]);
        $userGroups = $this->Users->Groups->find('list', ['limit' => 200]);
        $this->set(compact('user', 'primaryGroup', 'userGroups'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The {0} has been deleted.', __('user')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('user')));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function password_change()
    {
        $user = $this->Users->get($this->Auth->user('id'));
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Users->changePassword($user, $this->request->data)) {
                $this->Flash->success(__('Your password has been changed.'));
                $this->redirect(['controller' => 'Backend', 'action' => 'index']);
            } else {
                $this->Flash->error(__('Ups, something went wrong'));
            }
        }
        $this->set('user', $user);
    }
}
