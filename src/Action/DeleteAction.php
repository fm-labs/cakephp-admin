<?php

namespace Backend\Action;

class DeleteAction extends BaseEntityAction
{
    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __('Delete');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => 'trash'];
    }
}
