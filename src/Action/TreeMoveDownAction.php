<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Http\Response;

/**
 * Class TreeMoveDownAction
 *
 * @package Admin\Action
 */
class TreeMoveDownAction extends BaseEntityAction
{
    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return __d('admin', 'Move Down');
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return ['data-icon' => 'chevron-down'];
    }

    /**
     * @inheritDoc
     */
    public function _execute(Controller $controller): ?Response
    {
        if ($this->model()->hasBehavior('Tree')) {
            $entity = $this->entity();

            if ($this->model()->moveDown($entity)) {
                $controller->Flash->success(__d('admin', 'The {0} has been moved down.', $this->model()->getAlias()));
            } else {
                $controller->Flash->error(__d('admin', 'The {0} could not be moved. Please, try again.', $this->model()->getAlias()));
            }
        } else {
            $controller->Flash->error('Tree behavior not loaded for model ' . $this->model()->getAlias());
        }

        return $controller->redirect($controller->referer(['action' => 'index']));
    }
}
