<?php
return;
try {

    $menu = \Cake\ORM\TableRegistry::get('Content.Menus')->toMenu(1);
    $this->loadHelper('Bootstrap.Menu');
} catch (\Exception $ex) {
    echo $ex->getMessage();
    return;
}
?>
<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-sitemap"></i>
        <!--
        <span class="label label-success">4</span>
        -->
    </a>
    <ul class="dropdown-menu">
        <li class="header"><?= h($menu['title']); ?></li>
        <li>
            <!-- inner menu: contains the messages -->
            <?php
            $menu['class'] = 'menu';
            $menu['title'] = false;
            $menu['templates'] = ['navList' => '<ul class="{{class}}" style="max-height: 500px;">{{items}}</ul>'];
            $this->loadHelper('Bootstrap.Menu');
            echo $this->Menu->create($menu)->render();
            ?>
            <!-- /.menu -->
        </li>
        <li class="footer"><a href="#">See All Messages</a></li>
    </ul>
</li>