<?php
declare(strict_types=1);

namespace Admin\Controller\Base;

use Cake\Controller\Controller;

/**
 * Class BaseAdminController
 *
 * Use this class as a base controller for (app) controllers
 * which should run in admin context
 *
 * @package Admin\Controller\Base
 *
 * @property \Admin\Controller\Component\FlashComponent $Flash
 * @property \Cake\Controller\Component\PaginatorComponent $Paginator
 * @property \User\Controller\Component\AuthComponent $Auth
 *
 * @deprecated Use \Admin\Controller\Controller instead
 */
abstract class BaseAdminController extends Controller
{
    /**
     * @var array
     */
    public $actions = [
        'index' => 'Admin.Index',
        'view' => 'Admin.View',
        'add' => 'Admin.Add',
        'edit' => 'Admin.Edit',
    ];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @throws \Cake\Core\Exception\Exception
     * @return void
     */
    public function initialize(): void
    {
        // Configure Admin component
        if (!$this->components()->has('Admin')) {
            $this->loadComponent('Admin.Admin');
        }
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
