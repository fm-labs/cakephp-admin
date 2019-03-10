<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;

/**
 * Class PublishAction
 *
 * @package Backend\Action
 */
class PublishAction extends BaseEntityAction
{
    public $scope = ['table'];

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __d('backend', 'Publish');
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
        return (!$entity->get('is_published'));
    }

    /**
     * {@inheritDoc}
     */
    public function _execute(Controller $controller)
    {
        if (!$this->model()->hasBehavior('Publishable')) {
            $controller->Flash->error('Publishable behavior not loaded for model ' . $this->model()->alias());

            return $controller->redirect($controller->referer(['action' => 'index']));
        }

        $entity = $this->entity();
        if ($controller->request->is(['put', 'post'])) {
            $entity = $this->model()->patchEntity($entity, $controller->request->data(), [
                //'fieldList' => ['is_published', 'publish_start', 'publish_end'], //@TODO Enable fieldList
            ]);
            if ($this->model()->save($entity)) {
                $controller->Flash->success(__d('backend', 'Updated'));
                $controller->redirect($controller->referer(['action' => 'view', $entity->id]));
            } else {
                $controller->Flash->error(__d('backend', 'Update failed'));
            }
        }

        $controller->set('entity', $entity);
    }
}
