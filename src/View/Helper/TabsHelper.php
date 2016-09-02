<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 9/5/15
 * Time: 1:35 PM
 */

namespace Backend\View\Helper;

use Cake\Core\Configure;
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

        $params = array_merge(['title' => $title, 'url' => null, 'debugOnly' => false], $params);

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

        $debugEnabled = Configure::read('debug');

        // render tab menu
        $menuClass = "nav nav-tabs";
        foreach ($this->_items as $tabId => $item) {

            if ($debugEnabled !== true && $item['debugOnly'] === true)
                continue;

            $tabMenuId = $tabId . '-menu';
            $href = '#' . $tabId;

            // build tab link
            $tabLinkAttrs = [
                'role' => 'presentation',
                'id' => $tabMenuId
            ];

            if ($item['url']) {
                $tabLinkAttrs['data-url'] = $this->Url->build($item['url'], true);
                //$tabLinkAttrs['data-target'] = $tabId;
            }
            $tabLink = $this->Html->link($item['title'], $href, $tabLinkAttrs);

            // build tab menu item
            $menuItems .= $this->Html->tag('li', $tabLink, ['role' => 'tab', 'aria-controls' => $tabId]);

            //$js .= sprintf("$('#%s').tab(%s); ", $tabMenuId, json_encode($tabParams));
        }
        $menu = $this->Html->tag('ul', $menuItems, ['class' => $menuClass, 'role' => 'tablist']);

        // render tab contents
        $tabClass = "tab-pane";
        $i = 0;
        foreach ($this->_items as $tabId => $item) {
            $class = ($i++ > 0) ? $tabClass : $tabClass . " active";

            $attrs = ['id' => $tabId, 'role' => 'tabpanel'];
            //if ($item['url']) {
            //    $attrs['data-tab-url'] = $this->Url->build($item['url']);
            //}

            $tabs .= $this->Html->div($class, $item['content'], $attrs);

        }
        $tabs = $this->Html->div('tab-content', $tabs);


        //$script = sprintf("$(document).ready(function() { %s });", $js);
        $script = "";

        return $this->Html->div('be-tabs', $menu . $tabs . $this->Html->scriptBlock($script, ['safe' => false]));
    }
}