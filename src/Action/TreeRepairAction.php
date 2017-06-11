<?php

namespace Backend\Action;

use Cake\Controller\Controller;

/**
 * Class TreeRepairAction
 *
 * @package Backend\Action
 */
class TreeRepairAction extends BaseTableAction
{
    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __('Repair Tree');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'wrench'];
    }

    /**
     * {@inheritDoc}
     */
    public function _execute(Controller $controller)
    {
        if ($this->model()->hasBehavior('Tree')) {
            $this->model()->recover();
            $controller->Flash->success(__d('content', 'Tree for model {0} has been repaired', $this->model()->alias()));
        } else {
            $controller->Flash->error('Tree behavior not loaded for model ' . $this->model()->alias());
        }

        return $controller->redirect($controller->referer(['action' => 'index']));
    }
}
