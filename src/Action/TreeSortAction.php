<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Core\Plugin;
use Cake\Event\Event;

/**
 * Class TreeSortAction
 * @package Backend\Action
 */
class TreeSortAction extends BaseIndexAction
{
    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __d('backend', 'Sort');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'sitemap', 'data-modal' => true, 'data-modal-reload' => true];
    }

    /**
     * @param Controller $controller
     */
    public function _execute(Controller $controller)
    {
        try {
            if (!$this->model()->behaviors()->has('Tree')) {
                $controller->Flash->error(__d('backend','Model {0} has no Tree behavior attached', $controller->modelClass));
            }
        } catch (\Exception $ex) {
            $controller->Flash->error(__d('backend','Failed to load model {0}', $this->model()->alias()));
            return;
        }

        $controller->set('dataUrl', ['plugin' => 'Backend', 'controller' => 'Tree', 'action' => 'jstreeData', 'model' => $controller->modelClass]);
        $controller->set('sortUrl', ['plugin' => 'Backend', 'controller' => 'Tree', 'action' => 'jstreeSort', 'model' => $controller->modelClass]);
        $controller->render('Backend.tree_sort');
    }
}
