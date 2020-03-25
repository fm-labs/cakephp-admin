<?php
declare(strict_types=1);

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Datasource\QueryInterface;
use Cake\Routing\Router;
use Cake\Utility\Hash;

/**
 * Class IndexAction
 *
 * @package Backend\Action
 */
class IndexAction extends BaseAction
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
        'ajax' => false,
    ];

    protected $_defaultLimit = 15;
    protected $_maxLimit = 1000;

    /**
     * {@inheritDoc}
     */
    public function execute(Controller $controller)
    {
        // read config from controller view vars
        foreach (array_keys($this->_defaultConfig) as $key) {
            $this->_config[$key] = $controller->viewVars[$key] ?? $this->_defaultConfig[$key];
        }

        // detect model class
        if (!isset($controller->viewVars['modelClass'])) {
            $this->_config['modelClass'] = $controller->modelClass;
        }

        // load helpers
        if (isset($controller->viewVars['helpers'])) {
            $controller->viewBuilder()->setHelpers($controller->viewVars['helpers'], true);
        }

        // custom template
        if (isset($controller->viewVars['template'])) {
            $this->template = $controller->viewVars['template'];
        }

        // fields
        //$cols = $this->_normalizeColumns($this->_config['fields']);

        // UGLY WORKAROUND TO PREVENT BREAKING OLDER ADMIN PAGES USING THE DEPRECATED CONFIG SCHEME
        $cols = [];
        // fields whitelist
        if ($this->_config['fields.whitelist'] === true) {
            $cols = $this->_normalizeColumns($this->_config['fields']);
        } elseif (!empty($this->_config['fields.whitelist'])) {
            foreach ($this->_config['fields.whitelist'] as $whiteListed) {
                if (!array_key_exists($whiteListed, $cols)) {
                    $cols[$whiteListed] = [];
                }
            }
        }

        $normalized = $this->_normalizeColumns($this->_config['fields']);
        foreach ($normalized as $name => $col) {
            $cols[$name] = $col;
        }
        // END OF WORKAROUND

        if (empty($cols)) {
            // if no fields are defined, use first 10 columns from table schema
            $cols = array_slice($this->model()->getSchema()->columns(), 0, 10);
        }
        $cols = $this->_normalizeColumns($cols);

        // fields blacklist
        if ($this->_config['fields.blacklist']) {
            foreach ($this->_config['fields.blacklist'] as $blackListed) {
                if (array_key_exists($blackListed, $cols)) {
                    unset($cols[$blackListed]);
                }
            }
        }
        $this->_config['fields'] = $cols;

        // actions
        if ($this->_config['actions'] !== false) {
            //$event = $controller->dispatchEvent('Backend.Controller.buildIndexActions', ['actions' => $this->_config['actions']]);
            //$this->_config['actions'] = (array)$event->getData('actions');
        }

        //if ($this->_config['rowActions'] !== false) {
        //    $event = $controller->dispatchEvent('Backend.Action.Index.getRowActions', ['actions' => $this->_config['rowActions']]);
        //    $this->_config['rowActions'] = (array)$event->getData('actions');
        //}

        $response = null;
        try {
            $response = $this->_execute($controller);
        } catch (\Exception $ex) {
            $controller->Flash->error($ex->getMessage());
        } finally {
        }

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    protected function _execute(Controller $controller)
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
            ],
        ]);
        //$controller->set('actions', $this->_config['actions']);

        if ($this->model() && $this->model()->hasBehavior('Stats')) {
            $controller->set('tableStats', $this->model()->getStats());
        }

        $controller->set('toolbar.actions', [
            [__('Add'), ['action' => 'add'], ['data-icon' => 'plus']],
        ]);

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
            if ($this->request->getQuery('_filter')) {
                $_filter = Hash::flatten($this->request->getQuery('_filter'));
                $filter = array_filter($_filter, function ($val) {
                    return strlen(trim($val)) > 0;
                });
                $query->where($filter);
            }

            // search support with FriendsOfCake/Search plugin
            if ($this->_config['filter'] && $this->model()->behaviors()->has('Search')) {
                if ($this->controller->request->is(['post', 'put'])) {
                    $query->find('search', ['search' => $this->controller->request->getData()]);
                } elseif ($this->controller->request->getQuery()) {
                    $query->find('search', ['search' => $this->controller->request->getQuery()]);
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
                'url' => Router::url(['action' => $action, $row[$this->model()->getPrimaryKey()]]),
                'title' => $conf['label'],
                'attrs' => $conf['attrs'],
            ];
        }
//        foreach ($this->controller->Action->listActions() as $action) {
//            $_action = $this->controller->Action->getAction($action);
//
//            if ($_action instanceof EntityActionInterface && in_array('table', $_action->getScope()) /*&& $_action->isUsable($row)*/) {
//                $actions[$action] = [
//                    'title' => $_action->getLabel(),
//                    'url' => Router::url(['action' => $action, $row[$this->model()->getPrimaryKey()]]),
//                    'attrs' => $_action->getAttributes()];
//            }
//        }

        return $actions;
    }

    /**
     * @param array $columns Columns schema
     * @return array
     */
    protected function _normalizeColumns(array $columns)
    {
        $normalized = [];
        foreach ($columns as $col => $conf) {
            if (is_numeric($col)) {
                $col = $conf;
                $conf = [];
            }

            $normalized[$col] = $conf;
        }

        return $normalized;
    }
}
