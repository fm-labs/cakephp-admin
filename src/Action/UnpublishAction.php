<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;

/**
 * Class UnpublishAction
 *
 * @package Backend\Action
 */
class UnpublishAction extends BaseEntityAction
{
    protected $_scope = ['table'];

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __('Unpublish');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'eye-slash'];
    }

    /**
     * {@inheritDoc}
     */
    public function isUsable(EntityInterface $entity)
    {
        return ($entity->get('is_published'));
    }

    /**
     * {@inheritDoc}
     */
    public function _execute(Controller $controller)
    {
        if ($this->model()->hasBehavior('Publishable')) {
            $entity = $this->entity();
            $entity->is_published = false; //@TODO Use Publishabel behavior methods

            if ($this->model()->save($entity)) {
                $controller->Flash->success(__('Unpublished'));
            } else {
                $controller->Flash->error(__('Unpublish failed'));
            }
        } else {
            $controller->Flash->error('Publishable behavior not loaded for model ' . $this->model()->alias());

            return $controller->redirect($controller->referer(['action' => 'index']));
        }

        return $controller->redirect($controller->referer(['action' => 'index']));
    }
}
