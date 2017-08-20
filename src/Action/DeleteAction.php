<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Network\Exception\NotImplementedException;

class DeleteAction extends BaseEntityAction
{
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
        throw new NotImplementedException(get_class($this) . ' has no _execute() method implemented');
    }
}
