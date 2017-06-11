<?php

namespace Backend\Action;

use Cake\Controller\Controller;

/**
 * Class PublishAction
 *
 * @package Backend\Action
 */
class PublishAction extends BaseEntityAction
{
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

    /**
     * {@inheritDoc}
     */
    public function _execute(Controller $controller)
    {
        if ($this->model()->hasBehavior('Publishable')) {
            $entity = $this->entity();
            $entity->is_published = true; //@TODO Use Publishabel behavior methods

            if ($this->model()->save($entity)) {
                $controller->Flash->success(__('Published'));
            } else {
                $controller->Flash->error(__('Publish failed'));
            }
        } else {
            $controller->Flash->error('Publishable behavior not loaded for model ' . $this->model()->alias());

            return $controller->redirect($controller->referer(['action' => 'index']));
        }

        return $controller->redirect($controller->referer(['action' => 'index']));
    }
}
