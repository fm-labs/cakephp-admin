<?php
declare(strict_types=1);

namespace Admin\Action;

use Admin\Action\Interfaces\EntityActionInterface;
use Cake\Controller\Controller;
use Cake\Datasource\QueryInterface;
use Cake\Http\Response;
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
        'paginate' => true, // enable pagination
        'paginateSettings' => [], // pagination settings
        'filter' => false,
        'sortable' => true,
        'ajax' => false,
        'queryObj' => null, // table query object instance
        'filters' => [], // query conditions
        'contain' => [], // query contain param
        'limit' => 15, // query limit
        'maxLimit' => 1000, // query max limit
        'helpers' => [],
        'rowActions' => [],
        'actions' => [],

        // deprecated
        //'fields.whitelist' => [], // use 'include' instead
        //'fields.blacklist' => [], // use 'exclude' instead
        //'query' => [], // use 'paginateSettings' instead
    ];

    /**
     * @inheritDoc
     */
    public function execute(Controller $controller): ?Response
    {
        parent::execute($controller);

        // legacy settings
        if (!empty($this->_config['fields.whitelist'])) {
            $this->_config['include'] = $this->_config['fields.whitelist'];
            deprecationWarning('4.0.1', "Using the 'fields.whitelist' config is deprecated. Use 'include' instead.");
            unset($this->_config['fields.whitelist']);
        }
        if (!empty($this->_config['fields.blacklist'])) {
            $this->_config['exclude'] = $this->_config['fields.blacklist'];
            deprecationWarning('4.0.1', "Using the 'fields.blacklist' config is deprecated. Use 'exclude' instead.");
            unset($this->_config['fields.blacklist']);
        }

        // load helpers
        if ($this->_config['helpers']) {
            $controller->viewBuilder()->addHelpers($this->_config['helpers']);
        }

        // custom template
        if (isset($this->_config['template'])) {
            deprecationWarning('4.0.1', "Using the 'template' var is deprecated. Use getAction()->setTemplate() instead.");
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
    protected function _execute(Controller $controller): ?Response
    {
        $controller->set('dataTable', [
            'filter' => $this->_config['filter'],
            'paginate' => $this->_config['paginate'],
            'sortable' => $this->_config['sortable'],
            'model' => $this->_config['modelClass'],
            'fields' => $this->_config['fields'],
            'rowActions' => $this->_config['rowActions'],
            //'data' => $result,
            //'rowActionCallbacks' => [
            //    [$this, 'buildTableRowActions']
            //],
        ]);
        //$controller->set('actions', $this->_config['actions']);

        // @TODO Figure out why 'fields' config is lost, after calling _fetchResult !?!?
        $controller->set('result', $this->_fetchResult());

        if ($this->model() && $this->model()->hasBehavior('Stats')) {
            $controller->set('tableStats', $this->model()->getStats());
        }

        $toolbarActions = $controller->viewBuilder()->getVar('toolbar.actions');
        if ($controller->Action) {
            foreach ($controller->Action->listActions() as $actionName) {
                $action = $controller->Action->getAction($actionName);
                if (in_array('index', $action->getScope()) && !$action instanceof EntityActionInterface) {
                    $toolbarActions[] = [
                        $action->getLabel(),
                        ['action' => $actionName],
                        $action->getAttributes(),
                    ];
                }
            }
        }
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

            // build query
            if ($this->_config['queryObj'] instanceof QueryInterface) {
                $query = $this->_config['queryObj'];
            } else {
                $query = $this->model()->selectQuery();
                $query->applyOptions(['contain' => $this->_config['contain']]);
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
                // check if deprecated 'query' config is used
                if (!empty($this->_config['query'])) {
                    deprecationWarning('4.0.1',
                        "Using the 'query' config is deprecated. Use 'paginateSettings' instead.");
                    $this->_config['paginateSettings'] = array_merge((array)$this->_config['paginateSettings'], (array)$this->_config['query']);
                    unset($this->_config['query']);
                }

                $paginateSettings = (array)$this->_config['paginateSettings'] ?? $this->controller->paginate ?? [];
                if (!isset($paginateSettings['limit'])) {
                    $paginateSettings['limit'] = $this->_config['limit'];
                }
                if (!isset($paginateSettings['maxLimit'])) {
                    $paginateSettings['maxLimit'] = $this->_config['maxLimit'];
                }
                $result = $this->controller->paginate($query, $paginateSettings);
            } else {
                $result = $query->all();
            }

            foreach ($result as $row) {
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

        foreach ($actionComponent->listActions() as $actionName) {
            /** @var \Admin\Action\Interfaces\ActionInterface $action */
            $action = $actionComponent->getAction($actionName);

            /** @var \Admin\Action\Interfaces\EntityActionInterface $action */
            if ($action instanceof EntityActionInterface) {
                $actions[] = [
                    'url' => $action->getUrl($row[$this->model()->getPrimaryKey()]),
                    'title' => $action->getLabel(),
                    'attrs' => $action->getAttributes(),
                ];
            }
        }

        return $actions;
    }
}
