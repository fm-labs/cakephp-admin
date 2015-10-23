<?php $this->Html->addCrumb(__('Users'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($user->username); ?>
<div class="actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __('Edit {0}', __('User')),
                ['action' => 'edit', $user->id],
                ['class' => 'item', 'icon' => 'edit']
            ) ?>
            <?= $this->Ui->postLink(
                __('Delete {0}', __('User')),
                ['action' => 'delete', $user->id],
                ['class' => 'item', 'icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?>

            <?= $this->Ui->link(
                __('List {0}', __('Users')),
                ['action' => 'index'],
                ['class' => 'item', 'icon' => 'list']
            ) ?>
            <?= $this->Ui->link(
                __('New {0}', __('User')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>
            <div class="ui item dropdown">
                <div class="menu">
                    <?= $this->Ui->link(
                        __('List {0}', __('User Groups')),
                        ['controller' => 'Groups', 'action' => 'index'],
                        ['class' => 'item', 'icon' => 'list']
                    ) ?>
                    <?= $this->Ui->link(
                        __('New {0}', __('User Group')),
                        ['controller' => 'Groups', 'action' => 'add'],
                        ['class' => 'item', 'icon' => 'add']
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="users view">
    <h2 class="ui top attached header">
        <?= h($user->username) ?>
    </h2>
    <table class="ui attached celled striped table">
        <!--
        <thead>
        <tr>
            <th><?= __('Label'); ?></th>
            <th><?= __('Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __('Name') ?></td>
            <td><?= h($user->name) ?></td>
        </tr>
        <tr>
            <td><?= __('Primary User Group') ?></td>
            <td><?= $user->has('primary_user_group') ? $this->Html->link($user->primary_user_group->name, ['controller' => 'Groups', 'action' => 'view', $user->primary_user_group->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __('Username') ?></td>
            <td><?= h($user->username) ?></td>
        </tr>
        <tr>
            <td><?= __('Password') ?></td>
            <td><?= h($user->password) ?></td>
        </tr>
        <tr>
            <td><?= __('Email') ?></td>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <td><?= __('Email Verification Code') ?></td>
            <td><?= h($user->email_verification_code) ?></td>
        </tr>
        <tr>
            <td><?= __('Password Reset Code') ?></td>
            <td><?= h($user->password_reset_code) ?></td>
        </tr>
        <tr>
            <td><?= __('Login Last Login Ip') ?></td>
            <td><?= h($user->login_last_login_ip) ?></td>
        </tr>
        <tr>
            <td><?= __('Login Last Login Host') ?></td>
            <td><?= h($user->login_last_login_host) ?></td>
        </tr>
        <tr>
            <td><?= __('Block Reason') ?></td>
            <td><?= h($user->block_reason) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($user->id) ?></td>
        </tr>
        <tr>
            <td><?= __('Email Verification Expiry Timestamp') ?></td>
            <td><?= $this->Number->format($user->email_verification_expiry_timestamp) ?></td>
        </tr>
        <tr>
            <td><?= __('Password Change Min Days') ?></td>
            <td><?= $this->Number->format($user->password_change_min_days) ?></td>
        </tr>
        <tr>
            <td><?= __('Password Change Max Days') ?></td>
            <td><?= $this->Number->format($user->password_change_max_days) ?></td>
        </tr>
        <tr>
            <td><?= __('Password Change Warning Days') ?></td>
            <td><?= $this->Number->format($user->password_change_warning_days) ?></td>
        </tr>
        <tr>
            <td><?= __('Password Change Timestamp') ?></td>
            <td><?= $this->Number->format($user->password_change_timestamp) ?></td>
        </tr>
        <tr>
            <td><?= __('Password Expiry Timestamp') ?></td>
            <td><?= $this->Number->format($user->password_expiry_timestamp) ?></td>
        </tr>
        <tr>
            <td><?= __('Password Reset Expiry Timestamp') ?></td>
            <td><?= $this->Number->format($user->password_reset_expiry_timestamp) ?></td>
        </tr>
        <tr>
            <td><?= __('Login Failure Count') ?></td>
            <td><?= $this->Number->format($user->login_failure_count) ?></td>
        </tr>


            <tr>
                <td><?= __('Login Last Login Datetime') ?></td>
                <td><?= h($user->login_last_login_datetime) ?></td>
            </tr>
            <tr>
                <td><?= __('Login Failure Datetime') ?></td>
                <td><?= h($user->login_failure_datetime) ?></td>
            </tr>
            <tr>
                <td><?= __('Block Datetime') ?></td>
                <td><?= h($user->block_datetime) ?></td>
            </tr>
            <tr>
                <td><?= __('Created') ?></td>
                <td><?= h($user->created) ?></td>
            </tr>
            <tr>
                <td><?= __('Modified') ?></td>
                <td><?= h($user->modified) ?></td>
            </tr>

            <tr class="boolean">
                <td><?= __('Email Verification Required') ?></td>
                <td><?= $user->email_verification_required ? __('Yes') : __('No'); ?></td>
            </tr>
            <tr class="boolean">
                <td><?= __('Email Verified') ?></td>
                <td><?= $user->email_verified ? __('Yes') : __('No'); ?></td>
            </tr>
            <tr class="boolean">
                <td><?= __('Password Force Change') ?></td>
                <td><?= $user->password_force_change ? __('Yes') : __('No'); ?></td>
            </tr>
            <tr class="boolean">
                <td><?= __('Login Enabled') ?></td>
                <td><?= $user->login_enabled ? __('Yes') : __('No'); ?></td>
            </tr>
            <tr class="boolean">
                <td><?= __('Block Enabled') ?></td>
                <td><?= $user->block_enabled ? __('Yes') : __('No'); ?></td>
            </tr>
    </table>
</div>
<div class="related">
    <div class="">
    <h4><?= __('Related {0}', __('Groups')) ?></h4>
    <?php if (!empty($user->user_groups)): ?>
    <table class="ui table">
        <tr>
            <th><?= __('Id') ?></th>
            <th><?= __('Name') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($user->user_groups as $userGroups): ?>
        <tr>
            <td><?= h($userGroups->id) ?></td>
            <td><?= h($userGroups->name) ?></td>

            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'Groups', 'action' => 'view', $userGroups->id]) ?>

                <?= $this->Html->link(__('Edit'), ['controller' => 'Groups', 'action' => 'edit', $userGroups->id]) ?>
            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
