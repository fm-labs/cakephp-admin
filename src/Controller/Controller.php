<?php
namespace Backend\Controller;

use Backend\Controller\Component\FlashComponent;
use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Core\Configure;

/**
 * Class Controller
 *
 * Use this class as a base controller for (app) controllers
 * which should run in backend context
 *
 * @package Backend\Controller\Base
 *
 * @property AuthComponent $Auth
 * @property FlashComponent $Flash
 * @property PaginatorComponent $Paginator
 */
class Controller extends \Cake\Controller\Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->loadComponent('Backend.Backend');
        $this->loadComponent('Backend.Action');
        $this->loadComponent('Backend.Flash');
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
