<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Response;

class DeleteAction extends BaseEntityAction
{
    protected array $_defaultConfig = [];

    protected array $scope = ['table', 'form'];

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return __d('admin', 'Delete');
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return ['data-icon' => 'trash', 'class' => 'action-danger'];
    }

    protected function _execute(Controller $controller): ?Response
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
                            $controller->Flash->error(__d('admin', 'Failed to delete record(s)'));
                        }
                    } else {
                        $controller->Flash->error(__d('admin', 'Delete failed. No entity selected'));
                    }

                    //return $controller->redirect($controller->referer(['action' => 'index']));
                } else {
                    $controller->Flash->error(__d('admin', 'You must confirm to delete record'));
                }
            }

            $controller->set('entity', $entity);
            $controller->set('viewOptions', [
                'modelClass' => $this->model()->getRegistryAlias(), // @deprecated
                'defaultTable' => $this->model()->getRegistryAlias(),
            ]);
        } catch (RecordNotFoundException $ex) {
            $controller->Flash->error(__d('admin', 'Record not found'));

            return $controller->redirect($controller->referer(['action' => 'index']));
        }

        return null;
    }
}
