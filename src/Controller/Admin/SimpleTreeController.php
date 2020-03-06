<?php

namespace Backend\Controller\Admin;

use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;

/**
 * Class SimpleTreeController
 *
 * @package Backend\Controller\Admin
 */
class SimpleTreeController extends AppController
{

    /**
     * Index method
     */
    public function index()
    {
        $query = $this->request->getQuery();
        if (!isset($query['model'])) {
            $this->Flash->error(__d('backend', 'No model selected'));

            return;
        }
        $modelName = $query['model'];
        unset($query['model']);

        try {
            $Model = $this->loadModel($modelName);
            if (!$Model->behaviors()->has('SimpleTree')) {
                $this->Flash->warning(__d('backend', 'Model {0} has no SimpleTree behavior attached', $modelName));
            }
        } catch (\Exception $ex) {
            $this->Flash->error(__d('backend', 'Failed to load model {0}', $modelName));

            return;
        }

        $this->set('modelName', $modelName);
        $this->set('data', $Model->find('sorted')->where($query)->all());
        $this->set('sortUrl', ['controller' => 'SimpleTree', 'action' => 'treeSort', 'model' => $modelName]);
    }

    /**
     * TreeSort method
     */
    public function treeSort()
    {
        $this->viewBuilder()->setClassName('Json');

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

                if (!$modelName || !$this->loadModel($modelName)) {
                    throw new NotFoundException("Table not found");
                }

                $model = $this->loadModel($modelName);
                if (!$model->behaviors()->has('SimpleTree')) {
                    throw new \Exception('Table has no Sortable behavior attached');
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
}
