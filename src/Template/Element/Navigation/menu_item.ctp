<li>
    <?= $this->Ui->link($menuItem['title'], $menuItem['url'], ['icon' => $menuItem['icon']]); ?>
    <?php if (!empty($menuItem['_children'])): ?>
    <?= $this->element('Backend.Navigation/menu', ['menu' => $menuItem['_children']]); ?>
    <?php endif; ?>
</li>