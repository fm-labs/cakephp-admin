<?php

namespace Backend\Action;

use Cake\Controller\Controller;
use Cake\Network\Exception\NotImplementedException;

/**
 * Class CopyAction
 *
 * ! Experimental | Unused !
 *
 * @package Backend\Action
 * @internal
 * @codeCoverageIgnore
 */
class CopyAction extends BaseEntityAction
{
    public $scope = ['table'];

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

    /**
     * {@inheritDoc}
     */
    protected function _execute(Controller $controller)
    {
        throw new NotImplementedException(__CLASS__ . " not implemented");
    }
}
