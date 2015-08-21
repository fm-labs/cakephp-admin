<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 4/19/15
 * Time: 6:54 PM
 */

namespace Backend\Controller\Admin;

use Backend\Controller\Admin\AbstractBackendController;
use Backend\Lib\Menu\Menu;

class DefaultController extends AbstractBackendController
{
    public function index()
    {

        $menu = new Menu();

        $item = $menu->add('Home', '/');
        $item->children->add('Subhome', '/sub');

        $item2 = $menu->add('News', ['controller' => 'News', 'action' => 'index']);
        $item2->children->add('Subnews', '#');



    }
}
