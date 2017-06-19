<?php

namespace Backend\Action;

/**
 * Class AddAction
 *
 * @package Backend\Action
 */
class AddAction extends BaseIndexAction
{
    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __('Add');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'plus'];
    }
}
