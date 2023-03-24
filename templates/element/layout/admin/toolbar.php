<?php
/**
 * @var \Cake\View\View $this
 */
$toolbarDisabled = $this->get('toolbar_disabled', false);
if ($toolbarDisabled) {
    return false;
}

// @todo Move automatic title assignment to helper or admin view
$title = $this->fetch('title');

$heading = $this->fetch('heading', $title);
if ($this->get('toolbar_noheading')) {
    $heading = false;
}

$this->loadHelper('Admin.Toolbar');
?>
<div class="main-toolbar">
    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-1 mb-3 border-bottom toolbar">
            <div>
                <?php if ($heading): ?>
                <h3><?= $heading; ?></h3>
                <?php endif; ?>
            </div>
            <div class="btn-toolbar">
                <?= $this->Toolbar->render(); ?>
            </div>
        </div>

    </div>
</div>
