<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;
use Cake\Network\Exception\NotImplementedException;

class DeleteAction extends BaseEntityAction
{
    protected $_scope = ['table', 'form'];

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __('Delete');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'trash'];
    }

    protected function _execute(Controller $controller)
    {
        $entity = $this->entity();
        if ($entity instanceof EntityInterface) {
            if ($this->model()->delete($entity)) {
                $controller->Flash->success(__('Deleted'));
            } else {
                $controller->Flash->error("Delete failed");
            }
        } else {
            $controller->Flash->error("Delete failed. No entity selected");
        }
        return $controller->redirect($controller->referer(['action' => 'index']));
    }
}
