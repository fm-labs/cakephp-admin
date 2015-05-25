<?php
namespace Backend\Controller\Admin;

use Backend\Controller\Admin\AppController;

/**
 * UserGroups Controller
 *
 * @property \Backend\Model\Table\UserGroupsTable $UserGroups
 */
class UserGroupsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('userGroups', $this->paginate($this->UserGroups));
        $this->set('_serialize', ['userGroups']);
    }

    /**
     * View method
     *
     * @param string|null $id User Group id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $userGroup = $this->UserGroups->get($id, [
            'contain' => ['PrimaryUsers', 'Users']
        ]);
        $this->set('userGroup', $userGroup);
        $this->set('_serialize', ['userGroup']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $userGroup = $this->UserGroups->newEntity();
        if ($this->request->is('post')) {
            $userGroup = $this->UserGroups->patchEntity($userGroup, $this->request->data);
            if ($this->UserGroups->save($userGroup)) {
                $this->Flash->success(__('The {0} has been saved.', __('user group')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('user group')));
            }
        }
        $this->set(compact('userGroup'));
        $this->set('_serialize', ['userGroup']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User Group id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $userGroup = $this->UserGroups->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userGroup = $this->UserGroups->patchEntity($userGroup, $this->request->data);
            if ($this->UserGroups->save($userGroup)) {
                $this->Flash->success(__('The {0} has been saved.', __('user group')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('user group')));
            }
        }
        $this->set(compact('userGroup'));
        $this->set('_serialize', ['userGroup']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User Group id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $userGroup = $this->UserGroups->get($id);
        if ($this->UserGroups->delete($userGroup)) {
            $this->Flash->success(__('The {0} has been deleted.', __('user group')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('user group')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
