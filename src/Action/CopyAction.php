<?php

namespace Backend\Action;

/**
 * Class CopyAction
 *
 * @package Backend\Action
 */
class CopyAction extends BaseEntityAction
{
    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __('Copy');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'clone'];
    }
}
