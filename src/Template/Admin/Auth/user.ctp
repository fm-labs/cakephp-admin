<?php
use Cake\Core\Configure;
?>
<?php $this->Breadcrumbs->add(__('User'), ['action' => 'user']); ?>
<?php $this->assign('title', $user['name']); ?>
<div class="users view container" style="text-align: center;">
    <h2>
        <?= h($user['name']); ?>
    </h2>
    <div>
        <div class="image">
            <?= $this->Ui->icon('user', ['class' => 'fa-5x']); ?>
        </div>
        <div class="content">
            <a class="header"><?= h($user['username']); ?></a>
            <div class="meta">
                <span class="date">Joined <?= h($user['created']->nice()) ?></span>
            </div>
            <div class="description">
                Email: <?= h($user['email']); ?>
            </div>
        </div>
        <div class="extra content">
            <?= $this->Ui->link(__('Goto Dashboard'),
                ['_name' => 'backend:admin:dashboard'],
                ['data-icon' => 'home', 'class' => 'btn btn-default btn-block']);
            ?>
        </div>
        <div class="extra content">
            <?= $this->Ui->link(__('Edit profile'),
                ['plugin' => 'User', 'controller' => 'Users', 'action' => 'edit', $user['id'], 'prefix' => false],
                ['data-icon' => 'edit', 'class' => 'btn btn-default btn-block']);
            ?>
        </div>
        <div class="extra content">
            <?= $this->Ui->link(__('Change password'),
                ['plugin' => 'User', 'controller' => 'Users', 'action' => 'password_change', 'prefix' => false],
                ['data-icon' => 'key', 'class' => 'btn btn-default btn-block']);
            ?>
        </div>
        <div class="extra content">
            <?= $this->Ui->link(__('Logout'),
                ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout'],
                ['data-icon' => 'logout', 'class' => 'btn btn-default btn-block']);
            ?>
        </div>
    </div>

    <?php debug($user); ?>
</div>
