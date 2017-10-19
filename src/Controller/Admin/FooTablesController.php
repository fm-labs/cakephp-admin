<?php

namespace Backend\Controller\Admin;

use Cake\Core\Configure;
use Cake\Network\Exception\BadRequestException;
use Cake\Utility\Inflector;

class FooTablesController extends AppController
{

    public function index()
    {
        $this->viewBuilder()->layout(false);
    }

    public function columns()
    {
        $this->viewBuilder()->className('Json');

        $modelName = $this->request->query('model');
        $Model = $this->loadModel($modelName);

        $fields = ['id', 'title'];

        $columns = [];
        foreach($Model->schema()->columns() as $colName) {
            //if (!in_array($colName, $fields)) continue;
            $columns[] = ['name' => $colName, 'title' => Inflector::humanize($colName)];
        }

        $this->set('columns', $columns);
        $this->set('_serialize', 'columns');
    }

    public function rows()
    {
        //Configure::write('debug', 0);
        $this->viewBuilder()->className('Json');

        $modelName = $this->request->query('model');
        $Model = $this->loadModel($modelName);

        //$data = $Model->find('all', ['media' => true])->contain([])->limit(5)->all()->toArray();

        $_modelName = pluginSplit($modelName);
        $limit = ($this->request->query('limit')) ?: 10;
        $page = ($this->request->query('page')) ?: 10;

        $this->paginate = [
            'media' => true,
            'limit' => 5
        ];
        $data = $this->paginate($Model)->toArray();

        //$data = ['options' => ['expanded' => true], 'value' => $data];
        array_walk($data, function(&$row) {

            $aRow = $row->toArray();
            array_walk($aRow, function(&$val, $key) {
                $val = ['options' => ['expanded' => false, 'classes' => '_'], 'value' => $val];
            });

            $row = ['options' => ['expanded' => false, 'classes' => '_'], 'value' => $aRow];
        });

        $this->set('data', $data);
        $this->set('count', $this->request['paging'][$_modelName[1]]['count']);
        $this->set('page', $this->request['paging'][$_modelName[1]]['page']);
        $this->set('pageCount', $this->request['paging'][$_modelName[1]]['pageCount']);
        $this->set('_serialize', ['data', 'count', 'page', 'pageCount']);
    }

    public function ajax()
    {
        $this->viewBuilder()->className('Json');

        $request = $this->request->query;
        $data = [];
        $model = null;

        if (isset($request['model'])) {
            $model = $request['model'];
            unset($request['model']);
        } else {
            throw new BadRequestException();
        }

        $Model = $this->loadModel($model);
        $query = $Model->find();

        $recordsFiltered = $recordsTotal = $query->count();

        $query->limit($request['length']);
        $query->offset($request['start']);

        //$data = $this->paginate($Model);

        if ($request['search'] && $request['search']['value']) {
            $query->where(['title LIKE' => sprintf('%%%s%%',$request['search']['value'])]);
            $recordsFiltered = $query->count();
        }

        /*
         * Ordering
         *
         * Example:
         * $request['order'] = [
         *     0 => ['column' => 0, 'dir' => 'asc']
         * ]
        */
        if ($request['order']) {
            $order = [];
            foreach ($request['order'] as $_order) {
                $_colIdx = $_order['column'];
                $_col = $request['columns'][$_colIdx];
                $orderField = $_col['data'];
                $orderDir = $_order['dir'];
                $order[$orderField] = $orderDir;
            }
            $query->order($order);
        }

        //$data = $query->all()->toArray();
        $draw = (isset($request['draw'])) ? $request['draw'] : -1;
        $data = $query->all()->toArray();

        $this->set(compact('model', 'request', 'draw', 'recordsTotal', 'recordsFiltered', 'data'));
        $this->set('_serialize', ['model', 'request', 'draw', 'recordsTotal', 'recordsFiltered', 'data']);
    }
}