<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cake\Core\Configure;

/**
 * Class AdminController
 *
 * Default Dashboard controller
 *
 * @package Admin\Controller\Admin
 */
class AdminController extends AppController
{
    public $actions = [
        'index' => 'Admin.Dashboard',
    ];

    /**
     * Default Dashboard
     *
     * @return void
     */
    public function index()
    {
        $this->set('dashboard.title', __d('admin', 'System Dashboard'));
        $this->set('dashboard.panels', Configure::read('Admin.Dashboard.Panels'));
        $this->Action->execute();
    }

    /**
     * Admin Dashboard
     *
     * @return void
     * @deprecated
     */
    public function admin()
    {
        $this->Flash->warning('Dashboard::admin is deprecated');
    }
}
