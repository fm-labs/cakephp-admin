<?php
use Backend\Lib\BackendNav;

$this->loadHelper('Bootstrap.Ui');
$this->loadHelper('Bootstrap.Menu');
?>
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <?= $this->Html->image('/backend/adminlte/dist/img/user2-160x160.jp', ['class' => 'img-circle', 'alt' => 'User Image']); ?>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?php
        $menuTemplates = [
            'navList' => '<ul class="{{class}}">{{title}}{{items}}</ul>',
            'navListTitle' => '<li class="header">{{content}}</li>',
            'navListItem' => '<li role="presentation" class="{{class}}"{{attrs}}>{{link}}</li>',
            'navListItemSubmenu' => '<li role="presentation" class="{{class}}"{{attrs}}>{{link}}<span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>{{submenu}}</li>',
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


        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">HEADER</li>
            <!-- Optionally, you can add icons to the links -->
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
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>