<?php
namespace Backend\Controller\Admin;

/**
 * Groups Controller
 *
 * @property \User\Model\Table\GroupsTable $Groups
 */
class UserGroupsController extends AppController
{

    public $modelClass = 'User.Groups';

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('userGroups', $this->paginate($this->Groups));
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
        $userGroup = $this->Groups->get($id, [
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
        $userGroup = $this->Groups->newEntity();
        if ($this->request->is('post')) {
            $userGroup = $this->Groups->patchEntity($userGroup, $this->request->data);
            if ($this->Groups->save($userGroup)) {
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
        $userGroup = $this->Groups->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userGroup = $this->Groups->patchEntity($userGroup, $this->request->data);
            if ($this->Groups->save($userGroup)) {
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
        $userGroup = $this->Groups->get($id);
        if ($this->Groups->delete($userGroup)) {
            $this->Flash->success(__('The {0} has been deleted.', __('user group')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('user group')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
