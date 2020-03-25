<?php
declare(strict_types=1);

namespace Backend\Controller\Base;

use Cake\Controller\Controller;

/**
 * Class BaseBackendController
 *
 * Use this class as a base controller for (app) controllers
 * which should run in backend context
 *
 * @package Backend\Controller\Base
 *
 * @property \Cake\Controller\Component\AuthComponent $Auth
 * @property \Backend\Controller\Component\FlashComponent $Flash
 * @property \Cake\Controller\Component\PaginatorComponent $Paginator
 *
 * @deprecated Use \Backend\Controller\Controller instead
 */
abstract class BaseBackendController extends Controller
{
    /**
     * @var array
     */
    public $actions = [
        'index' => 'Backend.Index',
        'view' => 'Backend.View',
        'add' => 'Backend.Add',
        'edit' => 'Backend.Edit',
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
        // Configure Backend component
        if (!$this->components()->has('Backend')) {
            $this->loadComponent('Backend.Backend');
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
