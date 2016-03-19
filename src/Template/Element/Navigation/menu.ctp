<ul>
    <?php foreach ($menu as $menuItem): ?>
    <?= $this->element('Backend.Navigation/menu_item', ['menuItem' => $menuItem]); ?>
    <?php endforeach; ?>
</ul>