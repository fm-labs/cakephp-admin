<?php
$this->loadHelper('Bootstrap.Menu');

if (!isset($menu)) {
    echo "No menu";

    return;
}

$items = [];
if ($menu->count() > 0) {
    $menu->rewind();
    $first = $menu->current();
    if (isset($first['children'])) {
        $items = $first['children'];
    }
}
?>
<li class="dropdown sys-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-gears"></i>
        <span class="hidden-xs"><?= __d('backend', 'System'); ?></span>
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