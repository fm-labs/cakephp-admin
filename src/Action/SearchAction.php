<?php
declare(strict_types=1);

namespace Admin\Action;

use Admin\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Controller;

/**
 * Class SearchAction
 * @package Admin\Action
 */
class SearchAction implements IndexActionInterface
{
    /**
     * {@inheritDoc}
     */
    public function getLabel(): string
    {
        return __d('admin', 'Search');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes(): array
    {
        return ['data-icon' => 'search'];
    }

    /**
     * {@inheritDoc}
     */
    public function execute(Controller $controller)
    {
        $controller->set('foo', 'bar');
        $controller->render('index');
    }

    /**
     * @return array List of scope strings
     */
    public function getScope(): array
    {
    }
}
