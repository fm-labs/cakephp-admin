<?php

namespace Backend\Controller\Admin;

use Cake\Http\Exception\BadRequestException;

class DataTablesController extends AppController
{

    public function index()
    {
        $this->viewBuilder()->setLayout(false);
    }

    public function ajax()
    {
        $this->viewBuilder()->setClassName('Json');

        $request = $this->request->getQuery();
        $request += ['search' => null, 'order' => null, 'draw' => null, 'start' => null, 'length' => null, 'paginate' => []];
        $data = [];
        $model = null;

        if (isset($request['model'])) {
            $model = $request['model'];
            //unset($request['model']);
        } else {
            throw new BadRequestException();
        }

        /** @var \Cake\ORM\Table $Model */
        $Model = $this->loadModel($model);
        $query = $Model->find();

        $recordsFiltered = $recordsTotal = $query->count();

        if ($request['search'] && $request['search']['value']) {
            $query->find('search', ['search' => ['q' => $request['search']['value']]]);
            //$query->where(['subject LIKE' => sprintf('%%%s%%',$request['search']['value'])]);
            $recordsFiltered = $query->count();
        }

        $limit = ($request['length']) ?: 100;
        $offset = ($request['start']) ?: 0;
        $page = abs(($offset + 1) / $limit) + 1;

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

        if ($request['paginate']) {
            $request['paginate']['limit'] = $limit;
            $request['paginate']['page'] = $page;
            $data = $this->paginate($query, $request['paginate']);
        } else {
            $query->limit($limit);
            $query->offset($offset);
            $data = $query->all();
        }

        $data = $data->toArray();

        if ($Model->behaviors()->has('Tree')) {
            $displayField = $Model->getDisplayField();
            $treeList = $Model->find('treeList', ['spacer' => '_ '])->toArray();
            for ($i = 0; $i < count($data); $i++) {
                $data[$i][$displayField] = $treeList[$data[$i]['id']];
            }
        }

        $draw = (isset($request['draw'])) ? $request['draw'] : -1;

        $this->set(compact('model', 'request', 'draw', 'recordsTotal', 'recordsFiltered', 'data'));
        $this->set('_serialize', ['model', 'request', 'draw', 'recordsTotal', 'recordsFiltered', 'data']);
    }
}
