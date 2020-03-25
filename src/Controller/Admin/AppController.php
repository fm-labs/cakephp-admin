<?php

namespace Backend\Controller\Admin;

/**
 * Class AppController
 *
 * @package Backend\Controller\Admin
 */
class AppController extends \App\Controller\Admin\AppController
{
    /**
     * {@inheritDoc}
     */
    public function initialize(): void
    {
        parent::initialize();

        // make sure the Backend component is loaded,
        // especially if a custom Admin\AppController is used
        if (!$this->components()->has('Backend')) {
            $this->components()->load('Backend');
        }
    }
}
