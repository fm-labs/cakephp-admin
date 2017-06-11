<?php

namespace Backend\Action;

/**
 * Class EditAction
 *
 * @package Backend\Action
 */
class EditAction extends BaseEntityAction
{
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
}
