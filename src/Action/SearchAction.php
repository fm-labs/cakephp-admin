<?php

namespace Backend\Action;

use Backend\Action\Interfaces\IndexActionInterface;
use Cake\Controller\Controller;

/**
 * Class SearchAction
 * @package Backend\Action
 */
class SearchAction implements IndexActionInterface
{
    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __('Search');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
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
}
