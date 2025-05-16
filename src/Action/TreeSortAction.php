<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Http\Response;
use Exception;

/**
 * Class TreeSortAction
 *
 * @package Admin\Action
 */
class TreeSortAction extends IndexAction
{
    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return __d('admin', 'Sort');
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return ['data-icon' => 'sitemap', 'data-modal' => true, 'data-modal-reload' => true];
    }

    /**
     * @param \Cake\Controller\Controller $controller
     */
    public function _execute(Controller $controller): ?Response
    {
        try {
            if (!$this->model()->behaviors()->has('Tree')) {
                $controller->Flash->error(__d('admin', 'Model {0} has no Tree behavior attached', $controller->modelClass));
            }
        } catch (Exception $ex) {
            $controller->Flash->error(__d('admin', 'Failed to load model {0}', $this->model()->getAlias()));

            return null;
        }

        $controller->set('dataUrl', ['plugin' => 'Admin', 'controller' => 'Tree', 'action' => 'jstreeData', 'model' => $controller->modelClass]);
        $controller->set('sortUrl', ['plugin' => 'Admin', 'controller' => 'Tree', 'action' => 'jstreeSort', 'model' => $controller->modelClass]);

        return $controller->render('Admin.Action/tree_sort');
    }
}
