<?php

namespace Backend\Controller\Admin;

use Cake\Core\Exception\Exception;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

//use Tree\Model\Behavior\SimpleTreeBehavior;

/**
 * Class DataTableController
 *
 * @package Backend\Controller\Admin
 * @deprecated Use SimpleTreeController instead
 */
class DataTableController extends AppController
{
    /**
     * Reorder
     */
    public function reorder()
    {
        $modelName = $this->request->query('model');
        $field = $this->request->query('field');
        $order = $this->request->query('order');
        $scope = $this->request->query('scope');

        if (!$modelName || !$this->_getModel($modelName)) {
            throw new BadRequestException("Table not found");
        }

        if ($this->_getModel($modelName)->reorder($scope, compact('field', 'order'))) {
            $this->Flash->success(__d('backend', 'Reordering complete'));
        } else {
            $this->Flash->error(__d('backend', 'Reordering failed'));
        }
        $this->redirect($this->referer());
    }

    /**
     * Sort
     */
    public function sort()
    {
        $modelName = $this->request->query('model');

        if (!$modelName || !$this->_getModel($modelName)) {
            throw new BadRequestException("Table not found");
        }

        $model = $this->_getModel($modelName);
        if (!$model->behaviors()->has('SimpleTree')) {
            $this->Flash->warning("Model $modelName is not an instance of SimpleTree Behavior");
        }

        $queryArgs = $this->request->query;
        unset($queryArgs['model']);

        $data = $model->find()
            ->where($queryArgs)
            ->order(['pos' => 'ASC'])
            ->all();

        $this->set(compact('data', 'modelName'));
    }

    /**
     * TableSort
     */
    public function tableSort()
    {
        $this->viewBuilder()->className('Json');

        $responseData = [];
        try {
            if ($this->request->is(['post', 'put'])) {
                $data = $this->request->data;

                $modelName = (isset($data['model'])) ? (string)$data['model'] : null;
                $id = (isset($data['id'])) ? (int)$data['id'] : null;
                $after = (isset($data['after'])) ? (int)$data['after'] : false;

                $responseData = [
                    'model' => $modelName,
                    'id' => $id,
                    'after' => $after,
                ];

                if (!$id) {
                    throw new BadRequestException('ID missing');
                }

                if (!$modelName || !$this->_getModel($modelName)) {
                    throw new NotFoundException("Table not found");
                }

                $model = $this->_getModel($modelName);
                if (!$model->behaviors()->has('SimpleTree')) {
                    throw new Exception('Table has no SimpleTree behavior attached');
                }

                $node = $model->get($id);
                $responseData['oldPos'] = $node->pos;

                $node = $model->moveAfter($node, $after);
                $responseData['newPos'] = $node->pos;

                $responseData['success'] = (bool)$node;
            }
        } catch (\Exception $ex) {
            $responseData['success'] = false;
            $responseData['error'] = $ex->getMessage();
        }

        //$this->autoRender = false;
        //$this->response->body(json_encode($responseData));

        $this->set('result', $responseData);
        $this->set('_serialize', 'result');
    }

    /**
     * @param $tableName
     * @return \Cake\ORM\Table
     */
    protected function _getModel($tableName)
    {
        return TableRegistry::get($tableName);
    }
}
