
<?php $this->Breadcrumbs->add(__d('admin', 'User'), ['action' => 'user']); ?>
<?php $this->assign('title', $user['name']); ?>
<div class="users view container" style="text-align: center;">

    <div>
        <div class="image" style="margin: 1em 0;">
            <?= $this->Ui->icon('user-circle', ['class' => 'fa-5x']); ?>
        </div>
        <div class="content">
            <p class="description">
                Email: <?= h($user['email']); ?>
            </p>

            <p class="meta">
                <span class="date">Joined <?= h($user['created']->nice()) ?></span>
            </p>
        </div>
        <div class="d-flex flex-column">
            <?=
            $this->Html->link(
                __d('admin', 'Goto Dashboard'),
                ['_name' => 'admin:index'],
                ['data-icon' => 'home', 'class' => 'btn btn-outline-secondary btn-block']
            ); ?>
            <?=
            $this->Html->link(
                __d('admin', 'Edit profile'),
                ['_name' => 'admin:auth:user:profile'],
                ['data-icon' => 'edit', 'class' => 'btn btn-outline-secondary btn-block']
            ); ?>
            <?php /*
            $this->Html->link(
                __d('admin', 'Change password'),
                ['plugin' => 'User', 'controller' => 'Users', 'action' => 'passwordReset', $user['id']],
                ['data-icon' => 'key', 'class' => 'btn btn-outline-secondary btn-block']
            ); */ ?>
            <?=
            $this->Html->link(
                __d('admin', 'Change password'),
                ['_name' => 'user:passwordchange'],
                ['data-icon' => 'key', 'class' => 'btn btn-outline-secondary btn-block']
            ); ?>
            <?=
            $this->Html->link(
                __d('admin', 'Logout'),
                ['_name' => 'admin:auth:user:logout'],
                ['data-icon' => 'sign-out', 'class' => 'btn btn-outline-secondary btn-block']
            ); ?>
        </div>


    </div>
</div>
