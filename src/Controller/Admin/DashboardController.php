<?php

namespace Backend\Controller\Admin;

use Cake\Core\Configure;

/**
 * Class DashboardController
 *
 * Default Dashboard controller
 *
 * @package Backend\Controller\Admin
 */
class DashboardController extends AppController
{
    public $actions = [
        'index' => 'Backend.Dashboard'
    ];

    /**
     * Default Dashboard
     */
    public function index()
    {
        $this->set('dashboard.title', __('System Dashboard'));
        $this->set('dashboard.panels', Configure::read('Backend.Dashboard.Panels'));
        $this->Action->execute();
    }

    /**
     * Backend Dashboard
     * @deprecated
     */
    public function backend()
    {
        $this->Flash->warning('Dashboard::backend is deprecated');
    }
}
