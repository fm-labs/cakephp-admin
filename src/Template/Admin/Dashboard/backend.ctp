<?php $this->Html->addCrumb(__('Backend'), ['controller' => 'Dashboard', 'action' => 'backend']); ?>
<?php
$this->Toolbar->addLink('Refresh', ['controller' => 'Dashboard', 'action' => 'index'], ['class' => 'backend-refresh', 'data-icon' => 'list']);
$this->Toolbar->addLink('View Logs', ['controller' => 'Logs', 'action' => 'index'], ['class' => 'backend-logs', 'data-icon' => 'list']);
?>
<div id="backend-dashboard" class="backend dashboard index">
    <h1 class="ui header"><i class="cubes icon"></i>Backend Dashboard</h1>
    <div class="ui doubling stackable four column grid">
        <div class="column">
            <div class="ui top attached segment">
                <h2 class="ui header">
                    <div class="content">
                        <i class="info icon"></i>System Info
                        <div class="sub header">View App details and Environment settings</div>
                    </div>
                </h2>
            </div>
            <div class="ui attached segment dashboard-item">
                <div class="ui list">
                    <?= $this->Ui->link('Config', ['controller' => 'System', 'action' => 'config'], ['class' => 'item', 'data-icon' => 'help']); ?>
                    <?= $this->Ui->link('PHP Info', ['controller' => 'System', 'action' => 'php'], ['class' => 'item', 'data-icon' => 'help']); ?>
                    <?= $this->Ui->link('Date & Time Info', ['controller' => 'System', 'action' => 'datetime'], ['class' => 'item', 'data-icon' => 'help']); ?>
                    <?= $this->Ui->link('Globals', ['controller' => 'System', 'action' => 'globals'], ['class' => 'item', 'data-icon' => 'help']); ?>
                    <?= $this->Ui->link('Session', ['controller' => 'System', 'action' => 'session'], ['class' => 'item', 'data-icon' => 'help']); ?>
                    <?= $this->Ui->link('Plugins', ['controller' => 'System', 'action' => 'plugins'], ['class' => 'item', 'data-icon' => 'help']); ?>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="ui top attached segment">
                <h2 class="ui header">
                    <div class="content">
                        <i class="lock icon"></i>Access Control
                        <div class="sub header">Manage Users and Roles</div>
                    </div>
                </h2>
            </div>
            <div class="ui attached segment dashboard-item">
                <div class="ui list">
                    <?= $this->Ui->link(
                        'Manage Users',
                        ['plugin' => 'User', 'controller' => 'Users', 'action' => 'index'],
                        ['class' => 'item', 'data-icon' => 'male']
                    ); ?>
                    <?= $this->Ui->link(
                        'Change password',
                        ['plugin' => 'User', 'controller' => 'Users', 'action' => 'password_change'],
                        ['class' => 'item', 'data-icon' => 'edit']
                    ); ?>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="ui top attached segment">
                <h2 class="ui header">
                    <div class="content">
                        <i class="browser icon"></i>Logs
                        <div class="sub header">View Log files and manage Log rotation</div>
                    </div>
                </h2>
            </div>
            <div class="ui attached segment dashboard-item">
                <div class="ui list">
                    <?= $this->Ui->link('View all Logs', ['controller' => 'Logs', 'action' => 'index'], ['class' => 'item']); ?>
                    <?= $this->Ui->link('Debug Log', ['controller' => 'Logs', 'action' => 'view', 'debug.log'], ['class' => 'item']); ?>
                    <?= $this->Ui->link('Error Log', ['controller' => 'Logs', 'action' => 'view', 'error.log'], ['class' => 'item']); ?>
                    <?= $this->Ui->link('Backend Log', ['controller' => 'Logs', 'action' => 'view', 'backend.log'], ['class' => 'item']); ?>
                    <?= $this->Ui->link('Auth Log', ['controller' => 'Logs', 'action' => 'view', 'auth.log'], ['class' => 'item']); ?>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="ui top attached segment">
                <h2 class="ui header">
                    <div class="content">
                        <i class="time icon"></i>Cronjobs
                        <div class="sub header">Manage scheduled tasks</div>
                    </div>
                </h2>
            </div>
            <div class="ui attached segment dashboard-item">

            </div>
        </div>
    </div>


    <div class="ui segment">
        <h2 class="ui header">
            <div class="content">
                <i class="time icon"></i>Session
            </div>
        </h2>
        <?php debug($_SESSION); ?>
    </div>
</div>
