<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 9/5/15
 * Time: 1:35 PM
 */

namespace Backend\View\Helper;

use Cake\View\Helper;

class TabsHelper extends Helper
{
    public $helpers = ['Html'];

    protected $_items = [];

    protected $_started = null;

    public function start($options = [])
    {
        $this->_items = [];
    }

    public function add($title, $options = [])
    {
        $this->end();

        $blockId = uniqid('tab');
        $this->_items[$blockId] = ['title' => $title, 'options' => $options, 'content' => null];
        $this->_started = $blockId;
        $this->_View->Blocks->start($blockId);
    }

    public function end()
    {
        if ($this->_started) {
            $this->_View->Blocks->end();
            $blockId = $this->_started;
            $this->_items[$blockId]['content'] = $this->_View->Blocks->get($blockId);
            $this->_View->Blocks->set($blockId, null);
            $this->_started = null;
        }
    }

    public function render()
    {
        $this->end();

        // render tab menu
        $menuClass = "ui top attached tabular menu";
        $menuItems = "";
        foreach ($this->_items as $tabId => $item) {
            $menuItems .= $this->Html->link($item['title'], '#', ['class' => 'item', 'data-tab' => $tabId]);
        }
        $menu = $this->Html->div($menuClass, $menuItems);

        // render segments
        $tabClass = "ui bottom attached tab segment";
        $tabs = "";
        $i = 0;
        foreach ($this->_items as $tabId => $item) {
            $class = ($i++ > 0) ? $tabClass : $tabClass . " active";
            $tabs .= $this->Html->div($class, $item['content'], ['data-tab' => $tabId]);
        }
        //$tabs = $this->Html->div('tabs', $tabs);

        return $this->Html->div('be-tabs', $menu . $tabs);
    }
}