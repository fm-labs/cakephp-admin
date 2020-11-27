<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;

/**
 * Class TreeRepairAction
 *
 * @package Admin\Action
 */
class TreeRepairAction extends BaseIndexAction
{
    /**
     * {@inheritDoc}
     */
    public function getLabel(): string
    {
        return __d('admin', 'Repair Tree');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes(): array
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
            $controller->Flash->success(__d('admin', 'Tree for model {0} has been repaired', $this->model()->getAlias()));
        } else {
            $controller->Flash->error('Tree behavior not loaded for model ' . $this->model()->getAlias());
        }

        return $controller->redirect($controller->referer(['action' => 'index']));
    }
}
