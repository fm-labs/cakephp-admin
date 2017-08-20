<?php

namespace Backend\Action;
use Cake\Controller\Controller;

/**
 * Class EditAction
 *
 * @package Backend\Action
 */
class EditAction extends BaseEntityAction
{
    public $noTemplate = true;

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __('Edit');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'pencil'];
    }

    protected function _execute(Controller $controller)
    {
        return $controller->render();
    }
}
