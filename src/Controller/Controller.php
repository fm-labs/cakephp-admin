<?php
declare(strict_types=1);

namespace Admin\Controller;

/**
 * Class Controller
 *
 * Use this class as a base controller for (app) controllers
 * which should run in admin context
 *
 * @package Admin\Controller\Base
 * @property \User\Controller\Component\AuthComponent $Auth
 * @property \Admin\Controller\Component\FlashComponent $Flash
 * @property \Cake\Controller\Component\PaginatorComponent $Paginator
 */
class Controller extends \Cake\Controller\Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     * @throws \Exception
     */
    public function initialize(): void
    {
        $this->loadComponent('Admin.Admin');
        $this->loadComponent('Admin.Action');
        $this->loadComponent('Admin.Flash');
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
