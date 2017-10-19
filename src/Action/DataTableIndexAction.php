<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Utility\Inflector;

/**
 * Class IndexAction
 *
 * @package Backend\Action
 */
class DataTableIndexAction extends IndexAction
{
    public $template = 'Backend.data_table_index';

    /**
     * @var array
     */
    protected $_defaultJsTable = [
        //'searching' => false,
        //'sorting' => true,
        //'paging' => true,
        'lengthChange' => false,
        'lengthMenu' =>  [ 10, 25, 50, 75, 100 ],
        //'limit' => 25
    ];

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'modelClass' => null,
        'paginate' => false,
        'filter' => false,
        'sortable' => false,
        'data' => [],
        'fields' => [],
        'fields.blacklist' => [],
        'fields.whitelist' => [],
        'rowActions' => [],
        'actions' => [],
        'query' => [],
        'limit' => null,
        'ajax' => false,
    ];

    /**
     * @var Table
     */
    protected $_model;

    /**
     * @param Controller $controller
     */
    public function _execute(Controller $controller)
    {
        // data
        $this->_controller = $controller;
        $this->_model = $Model = $this->model();
        $result = [];
        $extra = [];

        // query limit workaround
        $limit = (isset($this->_config['query']['limit'])) ? $this->_config['query']['limit'] : $this->_defaultLimit;
        $this->_config['query']['limit'] = 1000; // hard limit
        $this->_config['query']['maxLimit'] = 1000; // hard limit
        $extra['pageLength'] = $limit;

        // JSON data
        if ($controller->request->query('data') == true) {

            //Configure::write('debug', 0);
            $controller->viewBuilder()->className('Json');

            $query = $this->_model->find();
            $request = $controller->request->query;
            $request += ['search' => null, 'order' => null, 'draw' => null, 'start' => null, 'length' => null, 'paginate' => []];

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
                $data = $controller->paginate($query, $request['paginate']);
            } else {
                $query->limit($limit);
                $query->offset($offset);
                $data = $query->all();
            }

            $data = $data->toArray();

            if ($this->_model->behaviors()->has('Tree')) {
                $displayField = $this->_model->displayField();
                $treeList = $this->_model->find('treeList', ['spacer' => '_ '])->toArray();
                for ($i = 0; $i < count($data); $i++) {
                    $data[$i][$displayField] = $treeList[$data[$i]['id']];
                }
            }

            $draw = (isset($request['draw'])) ? $request['draw'] : -1;

            $controller->set(compact('model', 'request', 'draw', 'recordsTotal', 'recordsFiltered', 'data'));
            $controller->set('_serialize', ['model', 'request', 'draw', 'recordsTotal', 'recordsFiltered', 'data']);
            return;
        }

        // AJAX
        if ($this->_config['ajax']) {
            //$dataUrl = Router::url(['plugin' => 'Backend', 'controller' => 'DataTables', 'action' => 'ajax', 'model' => $this->_config['modelClass']]);
            $dataUrl = Router::url(['data' => 1]);
            //$controller->set('dataUrl', $dataUrl);
            $extra['ajax'] = $dataUrl;
            $extra['processing'] = true;
            $extra['serverSide'] = true;

        } else {
            $result = $this->_fetchResult();
        }

        $controller->set('result', $result);
        //$controller->set('result', []);
        $controller->set('dataTable', [
            'filter' => $this->_config['filter'],
            'paginate' => $this->_config['paginate'],
            'sortable' => $this->_config['sortable'],
            'model' => $this->_config['modelClass'],
            'fields' => $this->_config['fields'],
            'fieldsWhitelist' => $this->_config['fields.whitelist'],
            'fieldsBlacklist' => $this->_config['fields.blacklist'],
            'rowActions' => false,
            'extra' => $extra
        ]);
        $controller->set('actions', $this->_config['actions']);
        $controller->set('_serialize', ['result']);

        //$controller->render('Backend.data_table_index');
    }

    protected function _buildColumns(&$columns)
    {
        //foreach($columns as $name => $config) {
        return array_values($columns);
    }

    protected function _buildOrder(&$order, &$columns)
    {
        $_order = [];
        foreach ($order as $col => $dir) {
            $i = 0;
            reset($columns);
            do {
                if (key($columns) == $col) {
                    $_order[] = [$i, $dir];
                    break;
                }
                $i++;
            } while(next($columns));
        }
        return $_order;
    }
}
