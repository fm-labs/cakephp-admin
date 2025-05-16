<?php
declare(strict_types=1);

namespace Admin\Controller;

use Cake\Controller\Controller as BaseController;

/**
 * Class BaseAdminController
 *
 * Use this class as a base controller for (app) controllers
 * which should run in admin context
 *
 * @package Admin\Controller
 */
abstract class Controller extends BaseController
{
    /**
     * Initialization hook method.
     *
     * Makes sure the Admin component is loaded
     *
     * @throws \Cake\Core\Exception\Exception
     * @return void
     */
    public function initialize(): void
    {
        $this->loadComponent('Admin.Admin');
    }

    /**
     * Fallback controller authorization
     *
     * @return bool|null
     */
    public function isAuthorized()
    {
        return null;
    }
}
