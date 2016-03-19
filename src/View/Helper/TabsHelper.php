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
    public $helpers = ['Html', 'Url'];

    protected $_items = [];

    protected $_started = null;

    public function start($options = [])
    {
        $this->_items = [];
    }

    public function add($title, $params = [])
    {
        $this->end();

        $params = array_merge(['title' => $title, 'url' => null], $params);

        $blockId = uniqid('tab');
        $this->_items[$blockId] = $params;
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

        $tabs = "";
        $js = "";
        $menuItems = "";

        // render tab menu
        $menuClass = "ui top attached tabular menu";
        foreach ($this->_items as $tabId => $item) {

            $tabMenuId = $tabId . '-menu';

            $attrs = ['class' => 'item', 'data-tab' => $tabId, 'id' => $tabMenuId];
            $tabParams = [];

            if ($item['url']) {
                $attrs['data-url'] = $this->Url->build($item['url'], true);
                $tabParams = [
                    /*
                    'auto' => true,
                    'history' => true,
                    'path' => $this->Url->build('/', true),
                    'apiSettings' => [
                        'url' => $this->Url->build($item['url'], false)
                    ]
                    */
                ];
            }

            $menuItems .= $this->Html->link($item['title'], '#', $attrs);

            $js .= sprintf("$('#%s').tab(%s); ", $tabMenuId, json_encode($tabParams));
        }
        $menu = $this->Html->div($menuClass, $menuItems);

        // render segments
        $tabClass = "ui bottom attached tab segment";
        $i = 0;
        foreach ($this->_items as $tabId => $item) {
            $class = ($i++ > 0) ? $tabClass : $tabClass . " active";

            $attrs = ['data-tab' => $tabId, 'id' => $tabId];
            //if ($item['url']) {
            //    $attrs['data-tab-url'] = $this->Url->build($item['url']);
            //}

            $tabs .= $this->Html->div($class, $item['content'], $attrs);

        }
        //$tabs = $this->Html->div('tabs', $tabs);


        //$script = sprintf("$(document).ready(function() { %s });", $js);
        $script = "";

        return $this->Html->div('be-tabs', $menu . $tabs . $this->Html->scriptBlock($script, ['safe' => false]));
    }
}