<?php
namespace Backend\Controller\Base;

use Backend\Controller\Component\FlashComponent;
use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Controller\Controller;
use Cake\Core\Configure;

/**
 * Class BaseBackendController
 *
 * Use this class as a base controller for (app) controllers
 * which should run in backend context
 *
 * @package Backend\Controller\Base
 *
 * @property AuthComponent $Auth
 * @property FlashComponent $Flash
 * @property PaginatorComponent $Paginator
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
    public function initialize()
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
