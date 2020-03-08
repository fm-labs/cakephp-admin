<?php

namespace Backend\Controller\Admin;

use Cake\Core\Configure;

/**
 * Class BackendController
 *
 * Default Dashboard controller
 *
 * @package Backend\Controller\Admin
 */
class BackendController extends AppController
{
    public $actions = [
        'index' => 'Backend.Dashboard',
    ];

    /**
     * Default Dashboard
     *
     * @return void
     */
    public function index()
    {
        $this->set('dashboard.title', __d('backend', 'System Dashboard'));
        $this->set('dashboard.panels', Configure::read('Backend.Dashboard.Panels'));
        $this->Action->execute();
    }

    /**
     * Backend Dashboard
     *
     * @return void
     * @deprecated
     */
    public function backend()
    {
        $this->Flash->warning('Dashboard::backend is deprecated');
    }
}
