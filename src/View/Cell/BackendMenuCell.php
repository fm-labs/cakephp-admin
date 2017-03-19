<?php
namespace Backend\View\Cell;

use Backend\Lib\Menu\Menu;
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

    protected function _getMenu()
    {
        $menu = new Menu();

        $event = new Event('Backend.Menu.get', $menu);
        $this->eventManager()->dispatch($event);

        return $menu;
    }

    protected function _getMenuFromPluginHooks()
    {
        $menu = [];
        $plugins = Backend::plugins();

        //check each plugin's AppController for a 'backendMenu' method
        $menuResolver = function ($plugin) use (&$menu) {

            $_menu = Backend::getMenu($plugin);
            if ($_menu) {
                $menu[] = $_menu;
            }
        };

        // Resolve app menus
        $menuResolver(null);
        // Resolve hooked plugin menus
        array_walk($plugins, $menuResolver);

        return $menu;
    }
}
