<?php

namespace Backend\Action;


use Cake\Controller\Controller;
use Cake\Core\Plugin;

class TreeIndexAction extends IndexAction
{
    public function _execute(Controller $controller)
    {
        $displayField = $this->model()->displayField();
        $treeList = $this->model()->find('treeList', ['spacer' => '_ '])->toArray();

        if (!isset($this->_config['fields'][$displayField])) {
            $this->_config['fields'][$displayField] = [];
        }
        if (!isset($this->_config['fields'][$displayField]['formatter'])) {
            $this->_config['fields'][$displayField]['formatter'] = function ($val, $row, $args, $view) use ($treeList) {
                return $view->Html->link($treeList[$row->id], ['action' => 'edit', $row->id]);
            };
        }

        if (!$this->_config['fields.whitelist']) {
            $this->_config['fields.whitelist'] = true;
        }

        if ($this->_config['rowActions'] !== false && empty($this->_config['rowActions'])) {
            $this->_config['rowActions'] = [
                [__d('backend','View'), ['action' => 'view', ':id'], ['class' => 'view']],
                [__d('backend','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
                [__d('backend','Move Up'), ['action' => 'moveUp', ':id'], ['class' => 'move-up']],
                [__d('backend','Move Down'), ['action' => 'moveDown', ':id'], ['class' => 'move-down']],
                [__d('backend','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
            ];
        }

        $controller->viewBuilder()->template('Backend.tree_index');

        parent::_execute($controller);
    }
}