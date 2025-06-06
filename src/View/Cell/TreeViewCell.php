<?php
declare(strict_types=1);

namespace Admin\View\Cell;

use Cake\View\Cell;

/**
 * TreeViewCell cell
 *
 * @property \Shop\Model\Table\ShopCategoriesTable $ShopCategories
 * @TODO Remove Shop plugin dependency
 */
class TreeViewCell extends Cell
{
    public $modelClass = 'Shop.ShopCategories';

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected array $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $this->loadModel('Shop.ShopCategories');
        $treeList = $this->ShopCategories->find('threaded')->toArray();

        $this->set('items', $treeList);
        $this->set('element', 'Admin.TreeView/list');
    }
}
