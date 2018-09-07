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
        return __d('backend','Delete');
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

        if ($controller->request->is(['post'])) {

            if ($controller->request->data('confirm') == true) {
                if ($entity instanceof EntityInterface) {
                    if ($this->model()->delete($entity)) {
                        $controller->Flash->success(__d('backend','Deleted'));
                        return $controller->redirect(['action' => 'index']);
                    } else {
                        $controller->Flash->error("Delete failed");
                    }
                } else {
                    $controller->Flash->error("Delete failed. No entity selected");
                }
                return $controller->redirect($controller->referer(['action' => 'index']));
            } else {
                $controller->Flash->error(__d('backend', "You must confirm to delete record"));
            }

        }

        $controller->set('entity', $entity);
    }
}
