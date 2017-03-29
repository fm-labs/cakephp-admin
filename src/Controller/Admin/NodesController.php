<?php
namespace Content\Controller\Admin;

use Cake\Event\Event;
use Content\Controller\Admin\AppController;

/**
 * Nodes Controller
 *
 * @property \Content\Model\Table\NodesTable $Nodes
 */
class NodesController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->loadComponent('Banana.Site');
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ParentNodes']
        ];
        $this->set('nodes', $this->paginate($this->Nodes));
        $this->set('_serialize', ['nodes']);
    }

    public function tree()
    {

    }

    public function treeData($siteId = null)
    {
        $this->viewBuilder()->className('Json');

        if (!$siteId) {
            $siteId = $this->Site->getSiteId();
        }

        $tree = $this->Nodes->toJsTree($siteId);
        $this->set('tree', $tree);
        $this->set('_serialize', 'tree');
    }

    public function treeSort()
    {

        $this->loadModel('Content.Nodes');

        $this->viewBuilder()->className('Json');
        $request = $this->request->data + ['nodeId' => null, 'oldParentId' => null, 'oldPos' => null, 'newParentId' => null, 'newPos' => null];

        $node = $this->Nodes->get($request['nodeId']);

        $this->Nodes->behaviors()->Tree->config('scope', ['site_id' => $node->site_id]);
        $this->Nodes->moveTo($node, $request['newParentId'], $request['newPos'], $request['oldPos']);

        $this->set('request', $request);
        $this->set('node',[
            'id' => $node->id,
            'site_id' => $node->site_id,
            'type' => $node->type,
            'typeid' => $node->typeid,
            'parent_id' => $node->parent_id,
            'level' => $node->level,
            'lft' => $node->lft,
            'url' => Router::url($node->getAdminUrl()),
        ]);
        $this->set('_serialize', ['request', 'message', 'node']);
    }

    /**
     * View method
     *
     * @param string|null $id Node id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $node = $this->Nodes->get($id, [
            'contain' => ['ParentNodes', 'ChildNodes']
        ]);
        $this->set('node', $node);
        $this->set('_serialize', ['node']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $node = $this->Nodes->newEntity();
        if ($this->request->is('post')) {
            $node = $this->Nodes->patchEntity($node, $this->request->data);
            if ($this->Nodes->save($node)) {
                $this->Flash->success(__('The {0} has been saved.', __('node')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('node')));
            }
        }
        $parentNodes = $this->Nodes->ParentNodes->find('list', ['limit' => 200]);
        $this->set(compact('node', 'parentNodes'));
        $this->set('_serialize', ['node']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Node id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $node = $this->Nodes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $node = $this->Nodes->patchEntity($node, $this->request->data);
            if ($this->Nodes->save($node)) {
                $this->Flash->success(__('The {0} has been saved.', __('node')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('node')));
            }
        }
        $parentNodes = $this->Nodes->ParentNodes->find('list', ['limit' => 200]);
        $this->set(compact('node', 'parentNodes'));
        $this->set('_serialize', ['node']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Node id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $node = $this->Nodes->get($id);
        if ($this->Nodes->delete($node)) {
            $this->Flash->success(__('The {0} has been deleted.', __('node')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('node')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
