<?php
namespace Backend\Controller\Admin;

use Backend\Controller\AppController;

/**
 * BackendUsers Controller
 *
 * @property \Backend\Model\Table\BackendUsersTable $BackendUsers
 */
class BackendUsersController extends AppController
{

    public $paginate = [
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('backendUsers', $this->paginate($this->BackendUsers));
        $this->set('_serialize', ['backendUsers']);
    }

    /**
     * View method
     *
     * @param string|null $id Backend User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $backendUser = $this->BackendUsers->get($id, [
            'contain' => []
        ]);
        $this->set('backendUser', $backendUser);
        $this->set('_serialize', ['backendUser']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $backendUser = $this->BackendUsers->newEntity();
        if ($this->request->is('post')) {
            $backendUser = $this->BackendUsers->patchEntity($backendUser, $this->request->data);
            if ($this->BackendUsers->save($backendUser)) {
                $this->Flash->success('The backend user has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The backend user could not be saved. Please, try again.');
            }
        }
        $this->set(compact('backendUser'));
        $this->set('_serialize', ['backendUser']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Backend User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $backendUser = $this->BackendUsers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $backendUser = $this->BackendUsers->patchEntity($backendUser, $this->request->data);
            if ($this->BackendUsers->save($backendUser)) {
                $this->Flash->success('The backend user has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The backend user could not be saved. Please, try again.');
            }
        }
        $this->set(compact('backendUser'));
        $this->set('_serialize', ['backendUser']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Backend User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $backendUser = $this->BackendUsers->get($id);
        if ($this->BackendUsers->delete($backendUser)) {
            $this->Flash->success('The backend user has been deleted.');
        } else {
            $this->Flash->error('The backend user could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
