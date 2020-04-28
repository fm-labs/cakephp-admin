<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;

/**
 * Class PublishAction
 *
 * @package Admin\Action
 */
class PublishAction extends BaseEntityAction
{
    public $scope = ['table'];

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __d('admin', 'Publish');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'eye'];
    }

    public function isUsable(EntityInterface $entity)
    {
        return !$entity->get('is_published');
    }

    /**
     * {@inheritDoc}
     */
    public function _execute(Controller $controller)
    {
        if (!$this->model()->hasBehavior('Publish')) {
            $controller->Flash->error('Publish behavior not loaded for model ' . $this->model()->getAlias());

            return $controller->redirect($controller->referer(['action' => 'index']));
        }

        $entity = $this->entity();
        if ($controller->getRequest()->is(['put', 'post'])) {
            $entity = $this->model()->patchEntity($entity, $controller->getRequest()->getData(), [
                //'fieldList' => ['is_published', 'publish_start', 'publish_end'], //@TODO Enable fieldList
            ]);
            if ($this->model()->save($entity)) {
                $controller->Flash->success(__d('admin', 'Updated'));
                $controller->redirect($controller->referer(['action' => 'view', $entity->id]));
            } else {
                $controller->Flash->error(__d('admin', 'Update failed'));
            }
        }

        $controller->set('entity', $entity);
    }
}
