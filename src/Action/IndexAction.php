<?php
declare(strict_types=1);

namespace Admin\Action;

use Admin\Action\Interfaces\EntityActionInterface;
use Cake\Controller\Controller;
use Cake\Datasource\QueryInterface;
use Cake\Utility\Hash;
use Exception;
use function Cake\Core\deprecationWarning;

/**
 * Class IndexAction
 *
 * @package Admin\Action
 */
class IndexAction extends BaseAction
{
    protected array $_defaultConfig = [
        'modelClass' => null,
        'data' => [],
        'fields' => [], // map of field column
        'exclude' => [], // list of column names to exclude
        'include' => [], // list of column names to include
        'paginate' => true,
        'filter' => false,
        'sortable' => true,
        'ajax' => false,
        'queryObj' => null, // table query object instance
        'query' => [], // query options
        'filters' => [], // query conditions
        'contain' => [], // query contain param
        'limit' => null, // query limit
        'helpers' => [],
        'rowActions' => [],
        'actions' => [],

        // deprecated
        'fields.whitelist' => [],
        'fields.blacklist' => [],
    ];

    protected $_defaultLimit = 15;
    //protected $_maxLimit = 1000;

    /**
     * @inheritDoc
     */
    public function execute(Controller $controller): ?\Cake\Http\Response
    {
        parent::execute($controller);

        // legacy settings
        if (!empty($this->_config['fields.whitelist'])) {
            $this->_config['include'] = $this->_config['fields.whitelist'];
            unset($this->_config['fields.whitelist']);
        }
        if (!empty($this->_config['fields.blacklist'])) {
            $this->_config['exclude'] = $this->_config['fields.blacklist'];
            unset($this->_config['fields.blacklist']);
        }

        // detect model class
        //$this->_config['modelClass'] = $this->_config['modelClass'] ?? $controller->fetchTable()->getRegistryAlias();
        //$this->_config['modelClass'] = $this->_config['modelClass'] ?? $controller->fetchTable()->getRegistryAlias();

        // load helpers
        if ($this->_config['helpers']) {
            $controller->viewBuilder()->addHelpers($this->_config['helpers']);
        }

        // custom template
        if (isset($this->_config['template'])) {
            deprecationWarning("Using the 'template' var is deprecated. Use getAction()->setTemplate() instead.");
            $this->setTemplate($this->_config['template']);
        }

        // fields
        $cols = $this->_normalizeColumns($this->_config['fields']);

        if (empty($cols)) {
            // if no fields are defined, use first 10 columns from table schema
            $cols = array_slice($this->model()->getSchema()->columns(), 0, 10);
            $cols = $this->_normalizeColumns($cols);
        }

        // fields whitelist
        if ($this->_config['include'] === true) {
            $this->_config['include'] = array_keys($cols);
        }

        if (!empty($this->_config['include'])) {
            $cols = array_filter($cols, function ($key) {
                return in_array($key, $this->_config['include']);
            }, ARRAY_FILTER_USE_KEY);
        }

        // fields blacklist
        if (!empty($this->_config['exclude'])) {
            $cols = array_filter($cols, function ($key) {
                return !in_array($key, $this->_config['exclude']);
            }, ARRAY_FILTER_USE_KEY);
        }

        $this->_config['fields'] = $cols;

        //debug($this->_config);

        // actions
        //if ($this->_config['actions'] !== false) {
            //$event = $controller->dispatchEvent('Admin.Controller.buildIndexActions', ['actions' => $this->_config['actions']]);
            //$this->_config['actions'] = (array)$event->getData('actions');
        //}

        //if ($this->_config['rowActions'] !== false) {
        //    $event = $controller->dispatchEvent('Admin.Action.Index.getRowActions', ['actions' => $this->_config['rowActions']]);
        //    $this->_config['rowActions'] = (array)$event->getData('actions');
        //}

        try {
            $this->_execute($controller);
        } catch (Exception $ex) {
            $controller->Flash->error($ex->getMessage());
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    protected function _execute(Controller $controller): ?\Cake\Http\Response
    {
        $controller->set('dataTable', [
            'filter' => $this->_config['filter'],
            'paginate' => $this->_config['paginate'],
            'sortable' => $this->_config['sortable'],
            'model' => $this->_config['modelClass'],
            'fields' => $this->_config['fields'],
            'rowActions' => $this->_config['rowActions'],
            //'data' => $result,
            'rowActionCallbacks' => [
                //[$this, 'buildTableRowActions']
            ],
        ]);
        //$controller->set('actions', $this->_config['actions']);

        // @TODO Figure out why 'fields' config is lost, after calling _fetchResult !?!?
        $controller->set('result', $this->_fetchResult());

        if ($this->model() && $this->model()->hasBehavior('Stats')) {
            $controller->set('tableStats', $this->model()->getStats());
        }

        $toolbarActions = $controller->viewBuilder()->getVar('toolbar.actions');
        $toolbarActions[] = [__d('admin', 'Add'), ['action' => 'add'], ['data-icon' => 'plus']];
        $controller->set('toolbar.actions', $toolbarActions);

        $controller->viewBuilder()->setOption('serialize', ['result']);

        return null;
    }

    /**
     * @inheritDoc
     */
    protected function _fetchResult()
    {
        $result = null;
        if (!empty($this->_config['data'])) {
            $result = $this->_config['data'];
        } elseif ($this->model()) {
            //if ($this->_config['paginate'] === true) {
            //    $this->_config['paginate'] = (array)$controller->paginate;
            //}

            //if ($this->_config['filter'] === true && !$this->model()->behaviors()->has('Search')) {
            //    $this->_config['filter'] = false;
            //}

            //if ($this->_config['paginate']) {
                //$maxLimit = $this->_maxLimit;
                //$limit = (isset($this->_config['query']['limit'])) ? $this->_config['query']['limit'] : $this->_defaultLimit;
                //$limit = ($limit <= $maxLimit) ? $limit : $maxLimit;
                //$this->_config['query']['limit'] = $limit;
            //}

            // build query
            if ($this->_config['queryObj'] instanceof QueryInterface) {
                $query = $this->_config['queryObj'];
            } else {
                $query = $this->model()->selectQuery();
                $query->applyOptions(['contain' => $this->_config['contain']]);
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
                if ($this->controller->getRequest()->is(['post', 'put'])) {
                    $query->find('search', ['search' => $this->controller->getRequest()->getData()]);
                } elseif ($this->controller->getRequest()->getQuery()) {
                    $query->find('search', ['search' => $this->controller->getRequest()->getQuery()]);
                }
            }

            if ($this->_config['paginate']) {
                $result = $this->controller->paginate($query, $this->_config['query']);
            } else {
                $result = $query->all();
            }

            foreach ($result->items() as $row) {
                $row->set('_actions_', $this->buildTableRowActions($row));
            }
        }

        return $result;
    }

    /**
     * @param mixed $row Table row data
     * @return array List of named actions
     */
    public function buildTableRowActions(mixed $row): array
    {
        $actions = [];
        /** @var \Admin\Controller\Component\ActionComponent $actionComponent */
        $actionComponent = $this->controller->components()->get('Action');
        //foreach ($actionComponent->getActionRegistry()->with(EntityActionInterface::class) as $action) {
        foreach ($actionComponent->listActions() as $actionName) {
            /** @var \Admin\Action\Interfaces\ActionInterface $action */
            $action = $actionComponent->getAction($actionName);

            /** @var \Admin\Action\Interfaces\EntityActionInterface $action */
            if ($action instanceof EntityActionInterface) {
                $actions[] = [
                    //'url' => Router::url(['action' => $actionName, $row[$this->model()->getPrimaryKey()]]),
                    'url' => $action->getUrl($row[$this->model()->getPrimaryKey()]),
                    'title' => $action->getLabel(),
                    'attrs' => $action->getAttributes(),
                ];
            }
        }
//        foreach ($this->controller->Action->actions as $action => $conf) {
//            if ($conf['type'] != 'entity') {
//                continue;
//            }
//            $actions[$action] = [
//                'url' => Router::url(['action' => $action, $row[$this->model()->getPrimaryKey()]]),
//                'title' => $conf['label'],
//                'attrs' => $conf['attrs'],
//            ];
//        }
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
}
