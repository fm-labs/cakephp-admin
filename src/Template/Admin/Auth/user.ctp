<?php
use Cake\Core\Configure;
?>
<?php $this->Html->addCrumb(__('User'), ['action' => 'user']); ?>
<?php $this->assign('title', 'About Me'); ?>
<div class="users view">
    <h2 class="ui header">
        <?= h('About Me'); ?>
    </h2>
    <div class="ui card">
        <div class="image">
            <img>
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
                $this->get('be_dashboard_url'),
                ['icon' => 'user']);
            ?>
        </div>
        <div class="extra content">
            <?= $this->Ui->link(__('Edit profile'),
                ['controller' => 'Users', 'action' => 'edit', $user['id']],
                ['icon' => 'user']);
            ?>
        </div>
        <div class="extra content">
            <?= $this->Ui->link(__('Change password'),
                ['controller' => 'Users', 'action' => 'password_change'],
                ['icon' => 'user']);
            ?>
        </div>
        <div class="extra content">
            <?= $this->Ui->link(__('Logout'),
                ['controller' => 'Auth', 'action' => 'logout'],
                ['icon' => 'user']);
            ?>
        </div>
    </div>

    <?php debug($user); ?>
</div>
