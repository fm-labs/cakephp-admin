<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;

class TreeIndexAction extends IndexAction
{
    public function getLabel(): string
    {
        return __d('admin', 'Index');
    }

    public function _execute(Controller $controller)
    {
        $displayField = $this->model()->getDisplayField();

        // @todo Ensure model has TreeBehavior loaded
        $treeList = $this->model()->find('treeList', spacer: '_ ')->toArray();

        if (!isset($this->_config['fields'][$displayField])) {
            $this->_config['fields'][$displayField] = [];
        }
        if (!isset($this->_config['fields'][$displayField]['formatter'])) {
            $this->_config['fields'][$displayField]['formatter'] = function ($val, $row, $args, $view) use ($treeList) {
                return $view->Html->link($treeList[$row->id], ['action' => 'edit', $row->id]);
            };
        }

        if (empty($this->_config['include'])) {
            $this->_config['include'] = array_keys($this->_config['fields']);
        }

        parent::_execute($controller);
    }
}
