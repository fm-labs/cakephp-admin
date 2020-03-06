<?php

namespace Backend\Action;

use Cake\Controller\Controller;

class TreeMoveUpAction extends BaseEntityAction
{
    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __d('backend', 'Move Up');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'chevron-up'];
    }

    /**
     * {@inheritDoc}
     */
    public function _execute(Controller $controller)
    {
        if ($this->model()->hasBehavior('Tree')) {
            $entity = $this->entity();

            if ($this->model()->moveUp($entity)) {
                $controller->Flash->success(__d('backend', 'The {0} has been moved up.', $this->model()->getAlias()));
            } else {
                $controller->Flash->error(__d('backend', 'The {0} could not be moved. Please, try again.', $this->model()->getAlias()));
            }
        } else {
            $controller->Flash->error('Tree behavior not loaded for model ' . $this->model()->getAlias());
        }

        return $controller->redirect($controller->referer(['action' => 'index']));
    }
}
