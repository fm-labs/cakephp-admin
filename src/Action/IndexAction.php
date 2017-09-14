<?php

namespace Backend\Action;

use Backend\Action\Interfaces\EntityActionInterface;
use Cake\Controller\Controller;

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
        'filter' => true,
        'data' => [],
        'fields' => [],
        'fields.blacklist' => [],
        'fields.whitelist' => [],
        'rowActions' => [],
        'actions' => []
    ];

    /**
     * @param Controller $controller
     */
    public function _execute(Controller $controller)
    {
        // data
        $this->_controller = $controller;
        $this->_model = $Model = $this->model();
        $result = [];

        if ($this->_config['data']) {
            $result = $this->_config['data'];
        } elseif ($Model) {
            if ($this->_config['paginate'] === true) {
                $this->_config['paginate'] = (array)$controller->paginate;
            }

            if ($this->_config['filter'] === true && !$Model->behaviors()->has('Search')) {
                $this->_config['filter'] = false;
            }

            $query = $Model->find();

            // search support with FriendsOfCake/Search plugin
            if ($this->_config['filter']) {
                if ($controller->request->is(['post', 'put'])) {
                    $query->find('search', ['search' => $controller->request->data]);
                } elseif ($controller->request->query) {
                    $query->find('search', ['search' => $controller->request->query]);
                }
            }

            if ($this->_config['paginate']) {
                $result = $controller->paginate($query, $this->_config['paginate']);
            } else {
                $result = $query->all();
            }
        }

        /*
        if ($this->_config['rowActions'] !== false) {
            //$event = $controller->dispatchEvent('Backend.Action.Index.getRowActions', ['actions' => $this->_config['rowActions']]);
            $event = $controller->dispatchEvent('Backend.Controller.buildEntityActions', [
                'actions' => $this->_config['rowActions'],
            ]);
            $this->_config['rowActions'] = (array)$event->data['actions'];
        }
        */

        // render
        $controller->set('result', $result);
        $controller->set('dataTable', [
            'filter' => $this->_config['filter'],
            'paginate' => ($this->_config['paginate']) ? true : false,
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
        $controller->set('actions', $this->_config['actions']);
        $controller->set('_serialize', ['result']);
    }

    public function buildTableRowActions($row)
    {
        $actions = [];
        foreach ($this->_controller->Action->listActions() as $action) {
            $_action = $this->_controller->Action->getAction($action);

            if ($_action instanceof EntityActionInterface && $_action->hasScope('table') /*&& $_action->isUsable($row)*/) {
                $actions[$action] = ['title' => $_action->getLabel(), 'url' => ['action' => $action, $row['id']], 'attrs' => $_action->getAttributes()];
            }
        }
        return $actions;
    }
}
