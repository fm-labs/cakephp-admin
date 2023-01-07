<?php
/**
 * @var \Cake\View\View $this
 */
$this->loadHelper('Admin.Toolbar');
?>
<div class="main-toolbar">
    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-1 mb-3 border-bottom toolbar">
            <div>
                <h3><?= $this->fetch('title'); ?></h3>
            </div>
            <div class="btn-toolbar">
                <?= $this->Toolbar->render(); ?>
            </div>
        </div>

    </div>
</div>
