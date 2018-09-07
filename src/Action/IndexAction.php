<?php

namespace Backend\Action;

use Backend\Action\Interfaces\EntityActionInterface;
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
        'rowActions' => [],
        'actions' => [],
        'query' => [],
        'queryObj' => null,
        'limit' => null,
        'ajax' => false
    ];

    protected $_defaultLimit = 15;
    protected $_maxLimit = 1000;

//    public function getLabel()
//    {
//        if ($this->_config['modelClass']) {
//            list($plugin,$modelName) = pluginSplit($this->_config['modelClass']);
//            return __d('backend', 'List {0}', $modelName);
//        }
//
//        return parent::getLabel();
//    }

    /**
     * @param Controller $controller
     */
    public function _execute(Controller $controller)
    {
        // data
        $this->_controller = $controller;

        /*
        if ($this->_config['rowActions'] !== false) {
            //$event = $controller->dispatchEvent('Backend.Action.Index.getRowActions', ['actions' => $this->_config['rowActions']]);
            $event = $controller->dispatchEvent('Backend.Controller.buildEntityActions', [
                'actions' => $this->_config['rowActions'],
            ]);
            $this->_config['rowActions'] = (array)$event->data['actions'];
        }
        */

        $controller->set('result', $this->_fetchResult());
        $controller->set('dataTable', [
            'filter' => $this->_config['filter'],
            'paginate' => $this->_config['paginate'],
            'sortable' => $this->_config['sortable'],
            'model' => $this->_config['modelClass'],
            'fields' => $this->_config['fields'],
            'fieldsWhitelist' => $this->_config['fields.whitelist'],
            'fieldsBlacklist' => $this->_config['fields.blacklist'],
            'rowActions' => $this->_config['rowActions'],
            //'data' => $result,
            'rowActionCallbacks' => [
                [$this, 'buildTableRowActions']
            ]
        ]);
        //$controller->set('actions', $this->_config['actions']);

        if ($this->model() && $this->model()->hasBehavior('Stats')) {
            $controller->set('tableStats', $this->model()->getStats());
        }

        $controller->set('_serialize', ['result']);
    }

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

                // apply query conditions from request
                if ($this->_request->query('qry')) {
                    $query->where($this->_request->query('qry'));
                }
            }


            // search support with FriendsOfCake/Search plugin
            if ($this->_config['filter'] && $this->model()->behaviors()->has('Search')) {
                if ($this->_controller->request->is(['post', 'put'])) {
                    $query->find('search', ['search' => $this->_controller->request->data]);
                } elseif ($this->_controller->request->query) {
                    $query->find('search', ['search' => $this->_controller->request->query]);
                }
            }

            if ($this->_config['paginate']) {
                $result = $this->_controller->paginate($query, $this->_config['query']);
            } else {
                $result = $query->all();
            }

            $result->each(function($row) {
                $row->set('_actions_', $this->buildTableRowActions($row));
            });
        }

        return $result;
    }


    public function buildTableRowActions($row)
    {
        $actions = [];
        foreach ($this->_controller->Action->listActions() as $action) {
            $_action = $this->_controller->Action->getAction($action);

            if ($_action instanceof EntityActionInterface && $_action->hasScope('table') /*&& $_action->isUsable($row)*/) {
                $actions[$action] = [
                    'title' => $_action->getLabel(),
                    'url' => Router::url(['action' => $action, $row['id']]),
                    'attrs' => $_action->getAttributes()];
            }
        }
        return $actions;
    }
}
