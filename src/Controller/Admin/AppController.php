<?php

namespace Backend\Controller\Admin;

use Backend\Controller\BackendActionsTrait;
use Cake\Controller\Controller;

/**
 * Class AppController
 *
 * @package Backend\Controller\Admin
 */
class AppController extends Controller
{
    use BackendActionsTrait;

    /**
     * {@inheritDoc}
     */
    public function initialize()
    {
        $this->loadComponent('Backend.Backend');
    }
}
