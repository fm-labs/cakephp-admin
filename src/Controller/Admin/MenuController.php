<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cupcake\Menu\MenuItemCollection;
use Cake\Event\Event;

class MenuController extends AppController
{
    public $modelClass = false;

    public function index()
    {

        $menu = new MenuItemCollection();
        $this->getEventManager()->dispatch(new Event('Admin.Sidebar.build', $menu, ['request' => $this->request]));
        $this->set('admin.sidebar.menu', $menu);

        /*
        $menu = new MenuItemCollection();
        $menu->addItem('Test 1', '#');
        $menu->addItem('Test 2', '#', [], [
            'test2_a' => [
                'title' => '2nd Test here',
                'url' => '#'
            ],
            'test2_b' => [
                'title' => 'Still here',
                'url' => '#'
            ]
        ]);
        */

        $this->set(compact('menu'));
    }
}
