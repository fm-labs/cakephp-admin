<?php
/**
 * @var \Cake\View\View $this
 */
$this->loadHelper('Admin.Toolbar');

$title = $this->fetch('title');
$heading = $this->fetch('heading');
$toolbarDisabled = $this->get('toolbar_disabled', false);

if ($toolbarDisabled) {
    return false;
}
?>
<div class="main-toolbar">
    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-1 mb-3 border-bottom toolbar">
            <div>
                <?php if ($heading !== false): ?>
                <h3><?= $heading ?? $title; ?></h3>
                <?php endif; ?>
            </div>
            <div class="btn-toolbar">
                <?= $this->Toolbar->render(); ?>
            </div>
        </div>

    </div>
</div>
