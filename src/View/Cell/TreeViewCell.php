<?php
declare(strict_types=1);

namespace Backend\View\Cell;

use Cake\View\Cell;

/**
 * TreeViewCell cell
 *
 * @property \Shop\Model\Table\ShopCategoriesTable $ShopCategories
 * @TODO Remove Shop plugin dependency
 */
class TreeViewCell extends Cell
{
    public $modelClass = "Shop.ShopCategories";

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
        $this->loadModel('Shop.ShopCategories');
        $treeList = $this->ShopCategories->find('threaded')->toArray();

        $this->set('items', $treeList);
        $this->set('element', 'Backend.TreeView/list');
    }
}
