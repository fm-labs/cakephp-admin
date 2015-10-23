<?php
namespace User\Controller\Admin;

use User\Controller\Admin\AppController;

/**
 * Permissions Controller
 *
 * @property \User\Model\Table\PermissionsTable $Permissions
 */
class PermissionsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('permissions', $this->paginate($this->Permissions));
        $this->set('_serialize', ['permissions']);
    }

    /**
     * View method
     *
     * @param string|null $id Permission id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $permission = $this->Permissions->get($id, [
            'contain' => []
        ]);
        $this->set('permission', $permission);
        $this->set('_serialize', ['permission']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $permission = $this->Permissions->newEntity();
        if ($this->request->is('post')) {
            $permission = $this->Permissions->patchEntity($permission, $this->request->data);
            if ($this->Permissions->save($permission)) {
                $this->Flash->success(__('The {0} has been saved.', __('permission')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('permission')));
            }
        }
        $this->set(compact('permission'));
        $this->set('_serialize', ['permission']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Permission id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $permission = $this->Permissions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $permission = $this->Permissions->patchEntity($permission, $this->request->data);
            if ($this->Permissions->save($permission)) {
                $this->Flash->success(__('The {0} has been saved.', __('permission')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('permission')));
            }
        }
        $this->set(compact('permission'));
        $this->set('_serialize', ['permission']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Permission id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $permission = $this->Permissions->get($id);
        if ($this->Permissions->delete($permission)) {
            $this->Flash->success(__('The {0} has been deleted.', __('permission')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('permission')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
