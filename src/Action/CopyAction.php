<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Http\Exception\NotImplementedException;

/**
 * Class CopyAction
 *
 * ! Experimental | Unused !
 *
 * @package Admin\Action
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
        return __d('admin', 'Copy');
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
        throw new NotImplementedException(self::class . " not implemented");
    }
}
