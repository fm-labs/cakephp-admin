<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Banana\Menu\Menu;
use Cake\Event\Event;

class MenuController extends AppController
{
    public $modelClass = false;

    public function index()
    {

        $menu = new Menu();
        $this->getEventManager()->dispatch(new Event('Admin.Sidebar.build', $menu, ['request' => $this->request]));
        $this->set('admin.sidebar.menu', $menu);

        /*
        $menu = new Menu();
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
