<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cake\Http\Exception\BadRequestException;
use Cake\Log\Log;

/**
 * Class TreeController
 *
 * @package Admin\Controller\Admin
 */
class TreeController extends AppController
{
    /**
     * Index method
     */
    public function index()
    {
        $modelName = $this->request->getQuery('model');
        if (!$modelName) {
            $this->Flash->error(__d('admin', 'No model selected'));

            return;
        }
        try {
            /** @var \Cake\ORM\Table $Model */
            $Model = $this->loadModel($modelName);
            if (!$Model->behaviors()->has('Tree')) {
                $this->Flash->warning(__d('admin', 'Model {0} has no Tree behavior attached', $modelName));
            }
        } catch (\Exception $ex) {
            $this->Flash->error(__d('admin', 'Failed to load model {0}', $modelName));

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
        $this->viewBuilder()->setClassName('Json');

        $modelName = $this->request->getQuery('model');
        if (!$modelName) {
            throw new BadRequestException('Model name missing');
        }

        $tree = [];
        try {
            /** @var \Cake\ORM\Table $Model */
            $Model = $this->loadModel($modelName);
            $Model->addBehavior('Admin.JsTree');
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
        $this->viewBuilder()->setClassName('Json');

        $modelName = $this->request->getQuery('model');
        if (!$modelName) {
            throw new BadRequestException('Model name missing');
        }

        $request = $this->request->getData()
            + ['nodeId' => null, 'oldParentId' => null, 'oldPos' => null, 'newParentId' => null, 'newPos' => null];

        $node = null;
        try {
            /** @var \Cake\ORM\Table $Model */
            $Model = $this->loadModel($modelName);
            $Model->addBehavior('Admin.JsTree');

            $node = $Model->get($request['nodeId']);
            //$Model->behaviors()->Tree->setConfig('scope', ['site_id' => $node->site_id]);
            $node = $Model->moveTo($node, $request['newParentId'], $request['newPos'], $request['oldPos']);

            $result = ['success' => __d('admin', "Node has been moved")];
        } catch (\Exception $ex) {
            Log::error('TreeController::treeData: ' . $ex->getMessage());
            //throw new InternalErrorException($ex->getMessage());
            $result =  ['error' => $ex->getMessage()];
        }

        $this->set('request', $request);
        $this->set('node', $node);
        $this->set('result', $result);
        $this->set('_serialize', ['request', 'result', 'node']);
    }
}
