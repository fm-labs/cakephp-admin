<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Network\Exception\NotImplementedException;

/**
 * Class CopyAction
 *
 * @package Backend\Action
 */
class CopyAction extends BaseEntityAction
{
    protected $_scope = ['table'];

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __d('backend', 'Copy');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'clone'];
    }

    protected function _execute(Controller $controller)
    {
        throw new NotImplementedException(__CLASS__ . " not implemented");
    }
}
