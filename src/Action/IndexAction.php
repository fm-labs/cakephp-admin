<?php

namespace Backend\Action;


use Cake\Controller\Controller;
use Cake\Core\Plugin;
use Cake\Event\Event;

class IndexAction extends BaseAction
{
    protected $_defaultConfig = [
        'modelClass' => null,
        'paginate' => true,
        'filter' => true,
        'data' => [],
        'fields' => [],
        'fields.blacklist' => [],
        'fields.whitelist' => [],
        'rowActions' => [],
        'actions' => true
    ];

    public function _execute(Controller $controller)
    {
        if ($this->_config['paginate'] === true) {
            $this->_config['paginate'] = (array) $controller->paginate;
        }

        $Model = $this->model();
        $result = [];

        if ($this->_config['data']) {
            $result = $this->_config['data'];

        } elseif ($Model) {

            if ($this->_config['filter'] === true && !$Model->behaviors()->has('Search')) {
                $this->_config['filter'] = false;
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
        }


        if ($this->_config['rowActions'] !== false && empty($this->_config['rowActions'])) {
            $event = $controller->dispatchEvent('Action.Index.getRowActions');
            $this->_config['rowActions'] = (array) $event->result;
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

    public function implementedEvents()
    {
        return [
            'Action.Index.getRowActions' => [ 'callable' => 'getDefaultRowActions', 'priority' => 5 ]
        ];
    }

    public function getDefaultRowActions(Event $event)
    {
        $event->result[] = [__d('backend','View'), ['action' => 'view', ':id'], ['class' => 'view']];
        $event->result[] = [__d('backend','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']];
        $event->result[] = [__d('backend','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]];
    }
}