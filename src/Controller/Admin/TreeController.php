<?php

namespace Backend\Controller\Admin;

use Cake\Log\Log;
use Cake\Network\Exception\BadRequestException;

/**
 * Class TreeController
 *
 * @package Backend\Controller\Admin
 */
class TreeController extends AppController
{

    /**
     * Index method
     */
    public function index()
    {
        $modelName = $this->request->query('model');
        if (!$modelName) {
            $this->Flash->error(__d('backend','No model selected'));

            return;
        }
        try {
            $Model = $this->loadModel($modelName);
            if (!$Model->behaviors()->has('Tree')) {
                $this->Flash->warning(__d('backend','Model {0} has no Tree behavior attached', $modelName));
            }
        } catch (\Exception $ex) {
            $this->Flash->error(__d('backend','Failed to load model {0}', $modelName));

            return;
        }

        $this->set('dataUrl', ['action' => 'jstreeData', 'model' => $modelName]);
        $this->set('sortUrl', ['action' => 'jstreeSort', 'model' => $modelName]);
    }

    /**
     * JSON jsTree data
     */
    public function jstreeData()
    {
        $this->viewBuilder()->className('Json');

        $modelName = $this->request->query('model');
        if (!$modelName) {
            throw new BadRequestException('Model name missing');
        }

        $tree = [];
        try {
            $Model = $this->loadModel($modelName);
            $Model->addBehavior('Backend.JsTree');
            $tree = $Model->find('jstree')->toArray();
        } catch (\Exception $ex) {
            Log::error('TreeController::treeData: ' . $ex->getMessage());
            //throw new InternalErrorException($ex->getMessage());
        }

        $this->set('tree', $tree);
        $this->set('_serialize', 'tree');
    }

    /**
     * jsTree sort
     */
    public function jstreeSort()
    {
        $this->viewBuilder()->className('Json');

        $modelName = $this->request->query('model');
        if (!$modelName) {
            throw new BadRequestException('Model name missing');
        }

        $request = $this->request->data + ['nodeId' => null, 'oldParentId' => null, 'oldPos' => null, 'newParentId' => null, 'newPos' => null];

        try {
            $Model = $this->loadModel($modelName);
            $Model->addBehavior('Backend.JsTree');

            $node = $Model->get($request['nodeId']);
            //$Model->behaviors()->Tree->config('scope', ['site_id' => $node->site_id]);
            $node = $Model->moveTo($node, $request['newParentId'], $request['newPos'], $request['oldPos']);

            $result = ['success' => __d('backend',"Node has been moved")];
        } catch (\Exception $ex) {
            Log::error('TreeController::treeData: ' . $ex->getMessage());
            //throw new InternalErrorException($ex->getMessage());
            $result =  ['error' => $ex->getMessage()];
        }

        $this->set('request', $request);
        $this->set('node', $node);
        $this->set('result', $result);
        $this->set('_serialize', ['request', 'message', 'node']);
    }
}
