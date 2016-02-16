<?php
namespace Backend\Controller\Admin;

use Backend\Controller\BackendControllerInterface;
use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Backend\Controller\Component\FlashComponent;
use Cake\Core\Plugin;
use Cake\Event\Event;

/**
 * Class BackendAppController
 *
 * Use this class as a base controller for app controllers
 * which should run in backend context
 *
 * @package Backend\Controller
 *
 * @property AuthComponent $Auth
 * @property FlashComponent $Flash
 * @property PaginatorComponent $Paginator
 *
 * @deprecated Use BackendComponent instead
 */
abstract class AbstractBackendController extends Controller implements BackendControllerInterface
{
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
