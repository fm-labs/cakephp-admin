<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cake\Core\Configure;
use Cupcake\Ui\Ui;

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


        /*
        return $this->Action->execute();
        $dashboard = Ui::get('Admin.dashboard');
        $dashboard->add('panels', MySuperFancyPanel::class);

        $layout = Ui::get('Admin.layout');
        $layout->add('header', Header::class);
        $layout->add('header_nav1', HeaderNav::class);
        $layout->add('header_nav2', HeaderNav::class);
        $layout->add('header_panels_left', HeaderNav::class);
        $layout->add('header_panels_left', HeaderNav::class);
        $layout->add('header_panels_left', HeaderNav::class);
        $layout->add('header_panels_right', HeaderNav::class);
        */
    }
}
