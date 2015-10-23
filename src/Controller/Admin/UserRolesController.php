<?php
namespace User\Controller\Admin;

use User\Controller\Admin\AppController;

/**
 * Roles Controller
 *
 * @property \User\Model\Table\RolesTable $Roles
 */
class RolesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('roles', $this->paginate($this->Roles));
        $this->set('_serialize', ['roles']);
    }

    /**
     * View method
     *
     * @param string|null $id Role id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $role = $this->Roles->get($id, [
            'contain' => []
        ]);
        $this->set('role', $role);
        $this->set('_serialize', ['role']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $role = $this->Roles->newEntity();
        if ($this->request->is('post')) {
            $role = $this->Roles->patchEntity($role, $this->request->data);
            if ($this->Roles->save($role)) {
                $this->Flash->success(__('The {0} has been saved.', __('role')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('role')));
            }
        }
        $this->set(compact('role'));
        $this->set('_serialize', ['role']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Role id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $role = $this->Roles->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $role = $this->Roles->patchEntity($role, $this->request->data);
            if ($this->Roles->save($role)) {
                $this->Flash->success(__('The {0} has been saved.', __('role')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('role')));
            }
        }
        $this->set(compact('role'));
        $this->set('_serialize', ['role']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Role id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $role = $this->Roles->get($id);
        if ($this->Roles->delete($role)) {
            $this->Flash->success(__('The {0} has been deleted.', __('role')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('role')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
