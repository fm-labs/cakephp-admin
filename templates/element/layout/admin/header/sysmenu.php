<?php
/** @var array $items */
$items = $items ?? [];
$this->loadHelper('Bootstrap.Dropdown')
//$items = \Admin\Admin::getMenu('admin_system');
?>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-gears"></i>
        <span class="hidden-xs"><?= __d('admin', 'System'); ?></span>
    </a>
    <?= $this->Dropdown->menu($items); ?>
</li>