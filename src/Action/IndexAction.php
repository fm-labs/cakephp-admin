<?php

namespace Backend\Action;


use Cake\Controller\Controller;
use Cake\Core\Plugin;

class IndexAction extends BaseAction
{
    protected $_defaultConfig = [
        'modelClass' => null,
        'paginate' => true,
        'filter' => true,
        'fields' => [],
        'fields.blacklist' => [],
        'fields.whitelist' => [],
        'rowActions' => [],
        'actions' => true
    ];

    public function _execute(Controller $controller)
    {
        $Model = $this->model();

        if ($this->_config['paginate'] === true) {
            $this->_config['paginate'] = (array) $controller->paginate;
        }
        if ($this->_config['filter'] === true && !$Model->behaviors()->has('Search')) {
            $this->_config['filter'] = false;
        }

        if ($this->_config['rowActions'] !== false && empty($this->_config['rowActions'])) {
            $this->_config['rowActions'] = [
                [__d('backend','View'), ['action' => 'view', ':id'], ['class' => 'view']],
                [__d('backend','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
                [__d('backend','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
            ];
        }

        $query = $Model->find();

        // search support with FriendsOfCake/Search plugin
        if ($this->_config['filter']) {
            if ($controller->request->is(['post','put'])) {
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

        // we use Toolbar helper to render actions
        if ($this->_config['actions'] === true) {
            $this->_config['actions'] = [
                [__('Add'), ['action' => 'add'], ['class' => 'add']],
            ];
        }
        if ($this->_config['actions']) {
            $controller->set('toolbar.actions', $this->_config['actions']);
        }

        $controller->set('result', $result);
        $controller->set('dataTable', [
            //'viewVars' => ['shopCategories' => $controller->get('shopCategories')],
            'filter' => $this->_config['filter'],
            'paginate' => ($this->_config['paginate']) ? true : false,
            'model' => $this->_config['modelClass'],
            //'data' => $result,
            'fields' => $this->_config['fields'],
            'fieldsWhitelist' => $this->_config['fields.whitelist'],
            'fieldsBlacklist' => $this->_config['fields.blacklist'],
            'rowActions' => $this->_config['rowActions'],
        ]);
        $controller->set('_serialize', ['result']);

    }
}