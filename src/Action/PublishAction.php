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
    protected $_scope = ['table'];

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __('Publish');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'eye'];
    }

    public function hasForm()
    {
        return true;
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
                $controller->Flash->success(__('Updated'));
            } else {
                $controller->Flash->error(__('Update failed'));
            }
        }


        //return $controller->redirect($controller->referer(['action' => 'index']));
    }

}
