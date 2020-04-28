<?php
/**
 * @deprected Use UiHelper::menu() instead
 */
// Hide priviledged items for unpriviledged users
if (isset($menuItem['requireRoot']) && $this->request->getSession()->read('Admin.User.is_root') !== true) {
    return false;
}
?>
<li role="presentation">
    <?= $this->Ui->link($menuItem['title'], $menuItem['url'], ['data-icon' => $menuItem['data-icon']]); ?>
    <?php if (!empty($menuItem['_children'])): ?>
    <?= '' //$this->element('Admin.Navigation/menu', ['menu' => $menuItem['_children']]); ?>
    <?php endif; ?>
</li>