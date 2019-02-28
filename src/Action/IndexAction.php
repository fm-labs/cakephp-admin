<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Datasource\QueryInterface;
use Cake\Routing\Router;

/**
 * Class IndexAction
 *
 * @package Backend\Action
 */
class IndexAction extends BaseIndexAction
{
    /**
     * @var array
     */
    protected $_defaultConfig = [
        'modelClass' => null,
        'paginate' => true,
        'filter' => false,
        'sortable' => true,
        'data' => [],
        'fields' => [],
        'fields.blacklist' => [],
        'fields.whitelist' => [],
        'filters' => [],
        'rowActions' => [],
        'actions' => [],
        'query' => [],
        'queryObj' => null,
        'limit' => null,
        'ajax' => false
    ];

    protected $_defaultLimit = 15;
    protected $_maxLimit = 1000;

    /**
     * {@inheritDoc}
     */
    public function _execute(Controller $controller)
    {
        // data
        $this->controller = $controller;

        $controller->set('result', $this->_fetchResult());
        $controller->set('dataTable', [
            'filter' => $this->_config['filter'],
            'paginate' => $this->_config['paginate'],
            'sortable' => $this->_config['sortable'],
            'model' => $this->_config['modelClass'],
            'fields' => $this->_config['fields'],
            //'fieldsWhitelist' => $this->_config['fields.whitelist'],
            //'fieldsBlacklist' => $this->_config['fields.blacklist'],
            'rowActions' => $this->_config['rowActions'],
            //'data' => $result,
            'rowActionCallbacks' => [
                //[$this, 'buildTableRowActions']
            ]
        ]);
        //$controller->set('actions', $this->_config['actions']);

        if ($this->model() && $this->model()->hasBehavior('Stats')) {
            $controller->set('tableStats', $this->model()->getStats());
        }

        $controller->set('_serialize', ['result']);
    }

    /**
     * {@inheritDoc}
     */
    protected function _fetchResult()
    {
        $result = null;
        if ($this->_config['data']) {
            $result = $this->_config['data'];
        } elseif ($this->model()) {
            //if ($this->_config['paginate'] === true) {
            //    $this->_config['paginate'] = (array)$controller->paginate;
            //}

            //if ($this->_config['filter'] === true && !$this->model()->behaviors()->has('Search')) {
            //    $this->_config['filter'] = false;
            //}

            if ($this->_config['paginate']) {
                //$maxLimit = $this->_maxLimit;
                //$limit = (isset($this->_config['query']['limit'])) ? $this->_config['query']['limit'] : $this->_defaultLimit;
                //$limit = ($limit <= $maxLimit) ? $limit : $maxLimit;
                //$this->_config['query']['limit'] = $limit;
            }

            // build query
            if ($this->_config['queryObj'] instanceof QueryInterface) {
                $query = $this->_config['queryObj'];
            } else {
                $query = $this->model()->find();
                $query->applyOptions($this->_config['query']);
            }

            // apply query conditions from request
            if ($this->request->query('qry')) { //@deprecated Use _filter param instead
                $this->request->query['_filter'] = $this->request->query['qry'];
                unset($this->request->query['qry']);
            }
            if ($this->request->query('_filter')) {
                $filter = array_filter($this->request->query('_filter'), function ($val) {
                    return (strlen(trim($val)) > 0);
                });
                $query->where($filter);
            }

            // search support with FriendsOfCake/Search plugin
            if ($this->_config['filter'] && $this->model()->behaviors()->has('Search')) {
                if ($this->controller->request->is(['post', 'put'])) {
                    $query->find('search', ['search' => $this->controller->request->data]);
                } elseif ($this->controller->request->query) {
                    $query->find('search', ['search' => $this->controller->request->query]);
                }
            }

            if ($this->_config['paginate']) {
                $result = $this->controller->paginate($query, $this->_config['query']);
            } else {
                $result = $query->all();
            }

            $result->each(function ($row) {
                $row->set('_actions_', $this->buildTableRowActions($row));
            });
        }

        return $result;
    }

    /**
     * @param array $row Table row data
     * @return array List of named actions
     */
    public function buildTableRowActions($row)
    {
        $actions = [];
        foreach ($this->controller->Action->actions as $action => $conf) {
            if ($conf['type'] != 'entity') {
                continue;
            }
            $actions[$action] = [
                'url' => Router::url(['action' => $action, $row[$this->model()->primaryKey()]]),
                'title' => $conf['label'],
                'attrs' => $conf['attrs']
            ];
        }
//        foreach ($this->controller->Action->listActions() as $action) {
//            $_action = $this->controller->Action->getAction($action);
//
//            if ($_action instanceof EntityActionInterface && in_array('table', $_action->getScope()) /*&& $_action->isUsable($row)*/) {
//                $actions[$action] = [
//                    'title' => $_action->getLabel(),
//                    'url' => Router::url(['action' => $action, $row[$this->model()->primaryKey()]]),
//                    'attrs' => $_action->getAttributes()];
//            }
//        }

        return $actions;
    }
}
