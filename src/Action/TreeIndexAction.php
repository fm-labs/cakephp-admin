<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;

class TreeIndexAction extends IndexAction
{
    public function getLabel()
    {
        return __d('admin', 'Index');
    }

    public function _execute(Controller $controller)
    {
        $displayField = $this->model()->getDisplayField();
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

        //$controller->viewBuilder()->setTemplate('Admin.tree_index');

        parent::_execute($controller);
    }
}
