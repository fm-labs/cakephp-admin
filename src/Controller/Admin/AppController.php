<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

/**
 * Class AppController
 *
 * @package Admin\Controller\Admin
 */
class AppController extends \App\Controller\Admin\AppController
{
    /**
     * {@inheritDoc}
     */
    public function initialize(): void
    {
        parent::initialize();

        // make sure the Admin component is loaded,
        // especially if a custom Admin\AppController is used
        if (!$this->components()->has('Admin')) {
            $this->components()->load('Admin');
        }
    }
}
