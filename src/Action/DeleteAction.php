<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Network\Exception\NotImplementedException;

class DeleteAction extends BaseEntityAction
{
    protected $_scope = ['table', 'form'];

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __('Delete');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'trash'];
    }

    protected function _execute(Controller $controller)
    {
        $controller->Flash->error("Not implemented or not allowed");
        return $controller->redirect($controller->referer(['action' => 'index']));
    }
}
