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
class FooTableIndexAction extends IndexAction
{
    static public $maxLimit = 200;

    /**
     * @var Table
     */
    protected $_model;

    /**
     * @var array
    protected $_defaultConfig = [
        'modelClass' => null,
        'paginate' => false,
        'sortable' => false,
        'filter' => false,
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
    */

    public function __construct(Controller $controller, array $config = []) {

        $this->_defaultConfig['ajax'] = true;

        parent::__construct($controller, $config);
    }

    public function getLabel()
    {
        return __("Index");
    }

    /**
     * @param Controller $controller
     */
    public function _execute(Controller $controller)
    {
        $this->_controller = $controller;
        $this->_model = $Model = $this->model();
        $result = [];
        $extra = [];
        $dataUrl = null; // required for ajax mode

        // query limit workaround
        $limit = (isset($this->_config['query']['limit'])) ? $this->_config['query']['limit'] : $this->_defaultLimit;
        $extra['paging']['size'] = $limit;
        $this->_config['query']['limit'] = $limit; //self::$maxLimit; // hard limit
        $this->_config['query']['maxLimit'] = self::$maxLimit; // hard limit

        // JSON data
        if ($controller->request->query('rows') == true) {

            //Configure::write('debug', 0);
            $controller->viewBuilder()->className('Json');

            $result = $this->_fetchResult();

            $controller->set('data', $result);
            $controller->set('_serialize', 'data');
            return;
        }

        // AJAX
        if ($this->_config['ajax']) {
            //$dataUrl = Router::url(['plugin' => 'Backend', 'controller' => 'FooTables', 'action' => 'rows', 'model' => $this->_config['modelClass']]);
            $dataUrl = $this->_config['ajax'];
            if ($dataUrl === true) {
                $dataUrl = ['rows' => 1];
                $dataUrl += ['qry' => $this->_request->query('qry')];
            }
            $extra['_dataUrl'] = $dataUrl = Router::url($dataUrl);
        } else {
            $result = $this->_fetchResult();
        }

        // data
        $controller->set('result', $result);

        // render
        $controller->set('dataTable', [
            'filter' => $this->_config['filter'],
            'paginate' => $this->_config['paginate'],
            'sortable' => $this->_config['sortable'],
            'model' => $this->_config['modelClass'],
            'fields' => $this->_config['fields'],
            'fieldsWhitelist' => $this->_config['fields.whitelist'],
            'fieldsBlacklist' => $this->_config['fields.blacklist'],
            'rowActions' => $this->_config['rowActions'],
            'extra' => $extra
        ]);
        $controller->set('actions', $this->_config['actions']);
        $controller->set('rowsUrl', $dataUrl);

        $controller->set('_serialize', ['result']);
        //$controller->render('Backend.foo_table_index');
    }

    protected function _fetchResult()
    {
        $data = parent::_fetchResult()->toArray();


        if ($this->_config['ajax'] == true) {
            if ($this->_config['paginate']) {
                $_modelName = pluginSplit($this->_config['modelClass']);
                $count = $this->_request['paging'][$_modelName[1]]['count'];
                $page = $this->_request['paging'][$_modelName[1]]['page'];
                $pageCount = $this->_request['paging'][$_modelName[1]]['pageCount'];
            } else {
                $count = $pageCount = count($data);
                $page = 1;
            }

            array_walk($data, function(&$row) {

                $aRow = $row->toArray();
                array_walk($aRow, function(&$val, $key) {
                    //$val = ['options' => ['expanded' => false, 'classes' => '_'], 'value' => $val];
                });

                $row = ['options' => ['expanded' => false, 'classes' => '_'], 'value' => $aRow];
            });

            return compact('data', 'count', 'page', 'pageCount');
        }

        return $data;
    }
}
