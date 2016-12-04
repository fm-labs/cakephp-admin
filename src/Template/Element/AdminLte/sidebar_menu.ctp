<?php
//@todo Remove example markup from html

use Backend\Lib\BackendNav;

$this->loadHelper('Bootstrap.Ui');
$this->loadHelper('Bootstrap.Menu');

$menuTemplates = [
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

?>
<?php echo $this->Menu->create(['templates' => $menuTemplates, 'classes' => $menuClasses, 'items' => BackendNav::getMenu('app')])->render(); ?>
<?php echo $this->Menu->create(['title' => 'Plugins', 'templates' => $menuTemplates, 'classes' => $menuClasses, 'items' => BackendNav::getMenu('plugins')])->render(); ?>
<?php echo $this->Menu->create(['title' => 'System', 'templates' => $menuTemplates, 'classes' => $menuClasses, 'items' => BackendNav::getMenu('system')])->render(); ?>
<!-- Example markup below:
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
-->
<!-- /.sidebar-menu -->