<?php
$this->loadHelper('Bootstrap.Menu');

$items = \Admin\Admin::getMenu('admin_system');
?>
<li class="dropdown sys-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-gears"></i>
        <span class="hidden-xs"><?= __d('admin', 'System'); ?></span>
    </a>
    <ul class="dropdown-menu">
        <li class="header hidden"></li>
        <li>
        <?php
        echo $this->Menu->create([
            'classes' => [
                'menu' => 'menu',
            ],
            'items' => $items
        ])->render();
        ?>
        </li>
        <li class="footer hidden"></li>
    </ul>
</li>