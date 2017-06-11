<?php
namespace Backend\View\Cell;

use Banana\Menu\Menu;
use Cake\Event\Event;
use Cake\View\Cell;

/**
 * BackendMenu cell
 */
class BackendMenuCell extends Cell
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
        $this->set('menu', $this->_getMenu()->toArray());
    }

    /**
     * @return Menu
     */
    protected function _getMenu()
    {
        $menu = new Menu();

        $event = new Event('Backend.Menu.get', $menu);
        $this->eventManager()->dispatch($event);

        return $menu;
    }
}
