<?php
declare(strict_types=1);

namespace Admin\Action;

use Cake\Controller\Controller;
use Cake\Http\Exception\NotImplementedException;
use Cake\Http\Response;

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
    protected array $scope = ['table'];

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return __d('admin', 'Copy');
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return ['data-icon' => 'clone'];
    }

    /**
     * @inheritDoc
     */
    protected function _execute(Controller $controller): ?Response
    {
        throw new NotImplementedException(self::class . ' not implemented');
    }
}
