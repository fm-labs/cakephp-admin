<?php
return false;
$this->loadHelper('Bootstrap.Menu');

$menuTemplates = [
    'navLink' => '<a href="{{url}}"{{attrs}}><span>{{content}}</span></a>',
    'navList' => '<ul class="{{class}}">{{title}}{{items}}</ul>',
    'navListTitle' => '<li class="header">{{content}}</li>',
    //'navListItem' => '<li role="presentation" class="{{class}}"{{attrs}}>{{link}}</li>',
    //'navListItemSubmenu' => '<li role="presentation" class="{{class}}"{{attrs}}>{{link}}{{submenu}}</li>',
    'navSubmenuList' => '<ul class="{{class}}">{{items}}</ul>',
];
$menuClasses = [
    'menu' => 'sidebar-menu',
    'submenuItem' => 'treeview',
    'submenu' => 'treeview-menu',
    'item' => 'item',
    'activeMenu' => 'menu-open',
    'activeItem' => 'active',
    'trailMenu' => 'menu-open',
    'trailItem' => 'active',
];

//debug($menu->toArray());
?>
<!--Sidebar toggle button
<ul class="sidebar-menu">
    <li role="presentation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <i class="fa fa-list"></i>
            <span class="_sr-only">Toggle navigation</span>
        </a>
    </li>
</ul>
-->
<?php

foreach (['admin_primary', 'admin_secondary'] as $menuId) {
    //$menu = $this->get('admin.sidebar.menu');
    $menu = \Admin\Admin::getMenu($menuId);
    if (!$menu) {
        //echo "No menu";
        continue;
    }

    echo $this->Menu->create([
        'templates' => $menuTemplates,
        'classes' => $menuClasses,
        'items' => $menu->toArray(),
    ])->render();
}
