<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Http\Response;
use Cake\Routing\Router;

/**
 * Class IndexAction
 * ! Experimental | Unused !
 *
 * @package Admin\Action
 * @internal
 * @codeCoverageIgnore
 * @deprecated
 */
class FooTableIndexAction extends IndexAction
{
    public static $maxLimit = 200;

    /**
     * @inheritDoc
     */
    public function __construct(Controller $controller, array $config = [])
    {
        $this->_defaultConfig['ajax'] = true;

        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return __d('admin', 'Index');
    }

    /**
     * @inheritDoc
     */
    public function _execute(Controller $controller): ?Response
    {
        $this->controller = $controller;
        $result = [];
        $extra = [];
        $dataUrl = null; // required for ajax mode

        // query limit workaround
        //$limit = $this->_config['query']['limit'] ?? $this->_defaultLimit;
        //$extra['paging']['size'] = $limit;
        //$this->_config['query']['limit'] = $limit; //self::$maxLimit; // hard limit
        //$this->_config['query']['maxLimit'] = self::$maxLimit; // hard limit

        // JSON data
        if ($controller->getRequest()->getQuery('rows') == true) {
            //Configure::write('debug', 0);
            $controller->viewBuilder()->setClassName('Json');

            $result = $this->_fetchResult();

            $controller->set('data', $result);
            $controller->viewBuilder()->setOption('serialize', 'data');

            return null;
        }

        // AJAX
        if ($this->_config['ajax']) {
            //$dataUrl = Router::url(['plugin' => 'Admin', 'controller' => 'FooTables', 'action' => 'rows', 'model' => $this->_config['modelClass']]);
            $dataUrl = $this->_config['ajax'];
            if ($dataUrl === true) {
                $dataUrl = ['rows' => 1];
                $dataUrl += ['qry' => $this->request->getQuery('qry')];
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
            'extra' => $extra,
        ]);
        $controller->set('actions', $this->_config['actions']);
        $controller->set('rowsUrl', $dataUrl);

        $controller->viewBuilder()->setOption('serialize', ['result']);
        //$controller->render('Admin.foo_table_index');

        return null;
    }

    /**
     * @inheritDoc
     */
    protected function _fetchResult()
    {
        $data = parent::_fetchResult()->toArray();

        if ($this->_config['ajax'] == true) {
            if ($this->_config['paginate']) {
                $_modelName = pluginSplit($this->_config['modelClass']);
                $count = $this->request['paging'][$_modelName[1]]['count'];
                $page = $this->request['paging'][$_modelName[1]]['page'];
                $pageCount = $this->request['paging'][$_modelName[1]]['pageCount'];
            } else {
                $count = $pageCount = count($data);
                $page = 1;
            }

            array_walk($data, function (&$row): void {

                $aRow = $row->toArray();
                array_walk($aRow, function (&$val, $key): void {
                    //$val = ['options' => ['expanded' => false, 'classes' => '_'], 'value' => $val];
                });

                $row = ['options' => ['expanded' => false, 'classes' => '_'], 'value' => $aRow];
            });

            return compact('data', 'count', 'page', 'pageCount');
        }

        return $data;
    }
}
