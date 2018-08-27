<?php
$this->loadHelper('Bootstrap.Menu');
//return;
//try {
//
//    $menu = \Cake\ORM\TableRegistry::get('Content.Menus')->toMenu(1);
//    $this->loadHelper('Bootstrap.Menu');
//} catch (\Exception $ex) {
//    echo $ex->getMessage();
//    return;
//}

$menu = $this->get('backend.navbar.sysmenu');
if (!isset($menu)) {
    echo "No menu";
    return;
}
?>

<!-- inner menu: contains the messages -->
<?php
$menuClasses = [
                'menu' => 'nav navbar-nav',
                'submenuItem' => 'dropdown',
                'submenu' => 'dropdown-menu',
//                'item' => '',
                'itemWithChildren' => 'dropdown-toggle',
                //'itemWithChildren' => 'dropdown',
//                'activeMenu' => 'menu-open',
//                'activeItem' => 'active',
//                'trailMenu' => 'menu-open',
//                'trailItem' => 'active'
];
$menuTemplates = []; //['navList' => '<ul class="{{class}}" style="max-height: 500px;">{{items}}</ul>'];
echo $this->Menu->create(['templates' => $menuTemplates, 'classes' => $menuClasses, 'items' => $menu->getItems()])->render();
return;
?>
<!-- /.menu -->


<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-sitemap"></i>
        <!--
        <span class="label label-success">4</span>
        -->
    </a>
    <ul class="dropdown-menu">
        <li class="header">Some title></li>
        <li>
            <!-- inner menu: contains the messages -->
            <?php
            $menuClasses = [
//                'menu' => 'sidebar-menu',
//                'submenuItem' => 'treeview',
//                'submenu' => 'treeview-menu',
//                'item' => '',
//                'activeMenu' => 'menu-open',
//                'activeItem' => 'active',
//                'trailMenu' => 'menu-open',
//                'trailItem' => 'active'
            ];
            $menuTemplates = ['navList' => '<ul class="{{class}}" style="max-height: 500px;">{{items}}</ul>'];
            //echo $this->Menu->create(['templates' => $menuTemplates, 'classes' => $menuClasses, 'items' => $menu->getItems()])->render();
            ?>
            <!-- /.menu -->
        </li>
        <li class="footer"><a href="#">See All Messages</a></li>
    </ul>
</li>