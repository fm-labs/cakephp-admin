<?php
/**
 * @deprected Use UiHelper::menu() instead
 */
?>
<ul class="nav navbar-nav">
    <?php foreach ($menu as $menuItem): ?>
    <?= $this->element('Admin.Navigation/menu_item', ['menuItem' => $menuItem]); ?>
    <?php endforeach; ?>
</ul>