<?php
namespace Backend\View\Cell;

use Banana\Menu\Menu;
use Cake\Event\Event;
use Cake\View\Cell;

/**
 * SidebarMenu cell
 */
class SidebarMenuCell extends Cell
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
            $this->eventManager()->dispatch(new Event('Backend.Sidebar.build', $menu, ['request' => $this->request]));
            $this->set('backend.sidebar.menu', $menu);
        }
    }
}
