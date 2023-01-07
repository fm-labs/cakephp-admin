<?php $this->Breadcrumbs->add(__d('admin', 'User'), ['action' => 'user']); ?>
<div class="users view container" style="text-align: center;">
    <h2>
        UNAUTHENTICATED ADMIN USER
    </h2>

    <div>
        <div class="image" style="margin: 1em 0;">
            <?= $this->Ui->icon('user-circle', ['class' => 'fa-5x']); ?>
        </div>
        <div class="content">
            <div class="alert alert-danger">
                ATTENTION!<br />
                Your are running the Admin interface without authentication!
            </div>
            <p>
                <?= $this->Html->link(__('Enable admin authentication now'), '#'); ?>
            </p>
        </div>
        <div class="extra content">
            <?=
            $this->Ui->link(
                __d('admin', 'Goto Dashboard'),
                ['_name' => 'admin:system:dashboard'],
                ['icon' => 'home', 'class' => 'btn btn-outline-secondary btn-block']
            ); ?>
    </div>
</div>
