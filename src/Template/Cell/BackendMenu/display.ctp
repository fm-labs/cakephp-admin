<?php
//$this->loadHelper('Bootstrap.Ui');
$this->loadHelper('Bootstrap.Menu');

$menuTemplates = [
    'navLink' => '<a href="{{url}}"{{attrs}}><span>{{content}}</span></a>',
    'navList' => '<ul class="{{class}}">{{title}}{{items}}</ul>',
    'navListTitle' => '<li class="header">{{content}}</li>',
    'navListItem' => '<li role="presentation" class="{{class}}"{{attrs}}>{{link}}</li>',
    'navListItemSubmenu' => '<li role="presentation" class="{{class}}"{{attrs}}>{{link}}{{submenu}}</li>',
    'navSubmenuList' => '<ul class="{{class}}">{{items}}</ul>'
];
$menuClasses = [
    'menu' => 'sidebar-menu',
    'submenuItem' => 'treeview',
    'submenu' => 'treeview-menu',
    'item' => '',
    'activeMenu' => 'menu-open',
    'activeItem' => 'active',
    'trailMenu' => 'menu-open',
    'trailItem' => 'active'
];

if (!isset($menu)) {
    echo "No menu";
    return;
}

echo $this->Menu->create(['templates' => $menuTemplates, 'classes' => $menuClasses, 'items' => $menu])->render();
/**
 * AdminLTE Sidebar example markup
<ul class="sidebar-menu">
<li class="header">HEADER</li>
<li class="active"><a href="#"><i class="fa fa-link"></i> <span>Link</span></a></li>
<li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li>
<li class="treeview">
<a href="#"><i class="fa fa-link"></i> <span>Multilevel</span>
<span class="pull-right-container">
<i class="fa fa-angle-left pull-right"></i>
</span>
</a>
<ul class="treeview-menu">
<li><a href="#">Link in level 2</a></li>
<li><a href="#">Link in level 2</a></li>
</ul>
</li>
</ul>
 **/