<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Utility\Hash;
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
        //'lengthChange' => true,
        //'lengthMenu' =>  [ 10, 25, 50, 75, 100 ],
        //'pageLength' => 10
    ];

    protected $_defaultLimit = 10;

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'modelClass' => null,
        'paginate' => false, // Pagination params
        'paging' => false, // Enable pagination
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
        'queryObj' => null,
        'contain' => []
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
        $dtjsOpts = $this->_defaultJsTable;

        // query limit workaround
        $limit = (isset($this->_config['query']['limit'])) ? $this->_config['query']['limit'] : $this->_defaultLimit;
        $this->_config['query']['limit'] = 1000; // hard limit
        $this->_config['query']['maxLimit'] = 1000; // hard limit
        $dtjsOpts['pageLength'] = $limit;
        $dtjsOpts['paging'] = $this->_config['paging'];

        // JSON data
        if ($controller->request->query('data') == true) {

            //Configure::write('debug', 0);
            $controller->viewBuilder()->className('Json');

            $query = $this->_model->find();
            $request = $controller->request->query;
            $request += ['search' => null, 'order' => null, 'draw' => null, 'start' => null, 'length' => null, 'paginate' => [], 'columns' => []];

            $recordsFiltered = $recordsTotal = $query->count();

            /*
            if ($request['search'] && isset($request['search']['value'])) {
                $query->find('search', ['search' => ['q' => $request['search']['value']]]);
                //$query->where(['subject LIKE' => sprintf('%%%s%%',$request['search']['value'])]);
                $recordsFiltered = $query->count();
            }
            */

            $limit = ($request['length'] > 0) ? $request['length'] : 100;
            $offset = ($request['start'] > 0) ? $request['start'] : 0;
            $page = intval(floor(abs($offset / $limit)));

            /**
             * Selecting & Searching
             */
            if (isset($request['columns']) ) {

                /*
                $selectedFields = array_map(function($col) {
                    return $col['data'];
                }, (array)$request['columns']);
                */
                //debug($selectedFields);

                foreach ($request['columns'] as $_col) {
                    if (Hash::check($_col, 'search.value') && strlen(trim(Hash::get($_col, 'search.value'))) > 0) {
                        if (Hash::get($_col, 'search.regex', false) == true) {
                            $query->where([ $_col['data'] . ' REGEXP "' . Hash::get($_col, 'search.value') . '"']);
                        } else {
                            $query->where([ $_col['data'] . ' LIKE' => sprintf('%%%s%%', (string) Hash::get($_col, 'search.value'))]);
                        }
                    }
                }
            }

            /**
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
                    list($_plugin, $_field) = pluginSplit($orderField);
                    $_plugin = ($_plugin) ? Inflector::camelize(Inflector::pluralize($_plugin)) : $this->model()->alias();
                    $order[$_plugin . '.' . $_field] = $orderDir;
                }
                $query->order($order);
            }


            try {
                /**
                 * Contain
                 */
                if ($this->_config['contain']) {
                    $query->contain($this->_config['contain']);
                }

                if ($request['paginate']) {
                    $request['paginate']['limit'] = $limit;
                    $request['paginate']['page'] = $page;
                    $request['_paging'] = 'on';
                    $request['_paginate'] = $request['paginate'];
                    $data = $controller->paginate($query, $request['paginate']);
                    //$recordsFiltered = $data->count();
                } else {
                    $query->limit($limit);
                    $query->offset($offset);
                    $data = $query->all();
                    //$recordsFiltered = $data->count();
                }

            } catch (\Exception $ex) {
                debug('AN ERROR OCCUREDED: ' . $ex->getMessage());
                $controller->set('error', $ex->getMessage());
                $controller->set('data', []);
                $controller->set('_serialize', ['data', 'error']);
                return $controller->render();
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
            return $controller->render();
        }

        // AJAX
        if ($this->_config['ajax']) {
            //$dataUrl = Router::url(['plugin' => 'Backend', 'controller' => 'DataTables', 'action' => 'ajax', 'model' => $this->_config['modelClass']]);
            $dataUrl = Router::url(['data' => 1]);
            //$controller->set('dataUrl', $dataUrl);
            $dtjsOpts['ajax'] = $dataUrl;
            $dtjsOpts['processing'] = false;
            $dtjsOpts['serverSide'] = true;

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
            'extra' => $dtjsOpts
        ]);
        $controller->set('actions', $this->_config['actions']);
        $controller->set('_serialize', ['result']);

        //$controller->render('Backend.data_table_index');
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
