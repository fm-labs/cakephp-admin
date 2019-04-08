<?php
namespace Backend\View\Cell;

use Banana\Menu\Menu;
use Banana\Menu\MenuItem;
use Cake\Event\Event;
use Cake\View\Cell;

/**
 * SidebarMenu cell
 */
class SysMenuCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display()
    {
        // load sidebar menu
        if (!isset($this->viewVars['backend.sidebar.menu'])) {
            $menu = new Menu();
            $this->eventManager()->dispatch(new Event('Backend.SysMenu.build', $menu));

            $sysmenu = new Menu();
            $item = new MenuItem([
                'title' => 'System',
                'url' => ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index'],
                'data-icon' => 'gears',
                'children' => $menu
            ]);
            $sysmenu->addItem($item);
            $this->set('menu', $sysmenu);
        }
    }
}
