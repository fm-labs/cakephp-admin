<?php
namespace Backend\Controller\Admin;

use Backend\Controller\Admin\AppController;
use Attachment\Model\Table\AttachmentsTable;
use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;

/**
 * Attachments Controller
 *
 * @property \Attachment\Model\Table\AttachmentsTable $Attachments
 */
class AttachmentsController extends AppController
{
    public $modelClass = "Attachment.Attachments";

    public $locale;

    public function initialize()
    {
        parent::initialize();

        if (!Plugin::loaded('Attachment')) {
            throw new MissingPluginException(['Attachment']);
        }
        $this->_setModelClass('Attachment.Attachments');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $locale = $this->request->query('locale');
        $this->locale = ($locale) ? $locale : null;
        if ($this->locale) {
            $this->Attachments->enableI18n();
            $this->Attachments->locale($this->locale);
        }
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->set('locale', $this->locale);
    }

    public function attach()
    {
        $attachment = $this->Attachments->newEntity();
        if ($this->request->is('post')) {
            $attachment = $this->Attachments->patchEntity($attachment, $this->request->data);
            $attachment->filename = basename($attachment->filepath);
            if ($this->Attachments->save($attachment)) {
                $this->Flash->success(__('The {0} has been saved.', __('attachment')));
            } else {
                debug($attachment->errors());
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('attachment')));
            }
        } else {
            //$model = $this->request->query('model');
            //$modelId = $this->request->query('id');
            //$scope = $this->request->query('scope');
        }
        return $this->redirect($this->referer());

        $this->set(compact('attachment'));
        $this->set('_serialize', ['attachment']);
    }


    /**
     * Delete method
     *
     * @param string|null $id Attachment id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function detach($id = null)
    {
        //$this->request->allowMethod(['post', 'delete']);
        $attachment = $this->Attachments->get($id);
        if ($this->Attachments->delete($attachment)) {
            $this->Flash->success(__('The {0} has been deleted.', __('attachment')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('attachment')));
        }
        return $this->redirect($this->referer());
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('attachments', $this->paginate($this->Attachments));
        $this->set('_serialize', ['attachments']);
    }

    /**
     * View method
     *
     * @param string|null $id Attachment id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $attachment = $this->Attachments->get($id, [
            'contain' => []
        ]);
        $this->set('attachment', $attachment);
        $this->set('_serialize', ['attachment']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $attachment = $this->Attachments->newEntity();
        if ($this->request->is('post')) {
            $attachment = $this->Attachments->patchEntity($attachment, $this->request->data);
            if ($this->Attachments->save($attachment)) {
                $this->Flash->success(__('The {0} has been saved.', __('attachment')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('attachment')));
            }
        }
        $this->set(compact('attachment'));
        $this->set('_serialize', ['attachment']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Attachment id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $attachment = $this->Attachments->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $attachment = $this->Attachments->patchEntity($attachment, $this->request->data);
            if ($this->Attachments->save($attachment)) {
                $this->Flash->success(__('The {0} has been saved.', __('attachment')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('attachment')));
            }
        }
        $this->set(compact('attachment'));
        $this->set('_serialize', ['attachment']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Attachment id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        //$this->request->allowMethod(['post', 'delete']);
        $attachment = $this->Attachments->get($id);
        if ($this->Attachments->delete($attachment)) {
            $this->Flash->success(__('The {0} has been deleted.', __('attachment')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('attachment')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
