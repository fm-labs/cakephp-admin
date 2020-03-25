<?php
declare(strict_types=1);

namespace Backend\Controller;

/**
 * Class Controller
 *
 * Use this class as a base controller for (app) controllers
 * which should run in backend context
 *
 * @package Backend\Controller\Base
 *
 * @property \Cake\Controller\Component\AuthComponent $Auth
 * @property \Backend\Controller\Component\FlashComponent $Flash
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
