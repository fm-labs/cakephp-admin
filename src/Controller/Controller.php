<?php
namespace Backend\Controller;

use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Core\Configure;
use Backend\Controller\Component\FlashComponent;

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
     * @var array
     */
    public $actions = [
        //'index' => 'Backend.Index',
        //'view' => 'Backend.View',
        //'add' => 'Backend.Add',
        //'edit' => 'Backend.Edit',
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
        $this->loadComponent('Backend.Backend');
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
