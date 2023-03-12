<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\NotFoundException;

class DeleteAction extends BaseEntityAction
{
    public $scope = ['table', 'form'];

    /**
     * {@inheritDoc}
     */
    public function getLabel(): string
    {
        return __d('admin', 'Delete');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes(): array
    {
        return ['data-icon' => 'trash', 'class' => 'action-danger'];
    }

    protected function _execute(Controller $controller)
    {
        try {
            $entity = $this->entity();
            if ($controller->getRequest()->is(['post'])) {
                if ($controller->getRequest()->getData('confirm') == true) {
                    if ($entity instanceof EntityInterface) {
                        if ($this->model()->delete($entity)) {
                            $controller->Flash->success(__d('admin', 'Deleted'));
                            return $controller->redirect(['action' => 'index']);
                        } else {
                            $controller->Flash->error(__("Failed to delete record(s)"));
                        }
                    } else {
                        $controller->Flash->error(__("Delete failed. No entity selected"));
                    }

                    //return $controller->redirect($controller->referer(['action' => 'index']));
                } else {
                    $controller->Flash->error(__d('admin', "You must confirm to delete record"));
                }
            }

            $controller->set('entity', $entity);
        } catch (RecordNotFoundException $ex) {
            $controller->Flash->error(__("Record not found"));
            return $controller->redirect($controller->referer(['action' => 'index']));
        }
    }
}
