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
        return __d('backend', 'Search');
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

    /**
     * @return array List of scope strings
     */
    public function getScope()
    {

    }
}
