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

$menu = $this->get('backend.navbar.menu');
if (!isset($menu)) {
    echo "No menu";
    return;
}
?>

<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-sitemap"></i>
    </a>
    <?php
    $menuClasses = [
        'menu' => 'dropdown-menu',
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
</li>