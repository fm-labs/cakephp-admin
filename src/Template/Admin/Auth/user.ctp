<?php
use Cake\Core\Configure;
?>
<?php $this->Breadcrumbs->add(__d('backend','User'), ['action' => 'user']); ?>
<?php $this->assign('title', $user['name']); ?>
<div class="users view container" style="text-align: center;">
    <h2>
        <?= h($user['name']); ?>
    </h2>
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
        <div class="extra content">
            <?= $this->Ui->link(__d('backend','Goto Dashboard'),
                ['_name' => 'backend:admin:user:login'],
                ['icon' => 'home', 'class' => 'btn btn-default btn-block']);
            ?>
            <?= $this->Ui->link(__d('backend','Edit profile'),
                ['plugin' => 'User', 'controller' => 'Users', 'action' => 'edit', $user['id']],
                ['icon' => 'edit', 'class' => 'btn btn-default btn-block']);
            ?>
            <?= $this->Ui->link(__d('backend','Change password'),
                ['plugin' => 'User', 'controller' => 'Users', 'action' => 'password_change'],
                ['icon' => 'key', 'class' => 'btn btn-default btn-block']);
            ?>
            <?= $this->Ui->link(__d('backend','Logout'),
                ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout'],
                ['icon' => 'sign-out', 'class' => 'btn btn-default btn-block']);
            ?>
        </div>
    </div>
</div>
