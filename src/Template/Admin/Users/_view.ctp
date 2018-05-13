<?php $this->Breadcrumbs->add(__d('user','Users'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($user->username); ?>

<?php $this->Toolbar->addLink(
    __d('user','Edit {0}', __d('user','User')),
    ['action' => 'edit', $user->id],
    ['data-icon' => 'edit']
) ?>
<?php $this->Toolbar->addPostLink(
    __d('user','Delete {0}', __d('user','User')),
    ['action' => 'delete', $user->id],
    ['data-icon' => 'trash', 'confirm' => __d('user','Are you sure you want to delete # {0}?', $user->id)]) ?>

<?php $this->Toolbar->addLink(
    __d('user','List {0}', __d('user','Users')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('user','New {0}', __d('user','User')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->startGroup(__d('user','More')); ?>
<?php $this->Toolbar->addLink(
    __d('user','List {0}', __d('user','User Groups')),
    ['controller' => 'Groups', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('user','New {0}', __d('user','User Group')),
    ['controller' => 'Groups', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<?php $this->Toolbar->render(); ?>

<div class="users view">
    <h2 class="ui top attached header">
        <?= h($user->username) ?>
    </h2>
    <table class="ui attached celled striped compact table">
        <!--
        <thead>
        <tr>
            <th><?= __d('user','Label'); ?></th>
            <th><?= __d('user','Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __d('user','Name') ?></td>
            <td><?= h($user->name) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Primary Group') ?></td>
            <td><?= $user->has('primary_group') ? $this->Html->link($user->primary_group->name, ['controller' => 'UserGroups', 'action' => 'view', $user->primary_group->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Username') ?></td>
            <td><?= h($user->username) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Password') ?></td>
            <td>***** <?= $this->Html->link(__d('user','Change password'), ['action' => 'password_reset', $user->id]); ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Email') ?></td>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Email Verification Code') ?></td>
            <td><?= h($user->email_verification_code) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Password Reset Code') ?></td>
            <td><?= h($user->password_reset_code) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Login Last Login Ip') ?></td>
            <td><?= h($user->login_last_login_ip) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Login Last Login Host') ?></td>
            <td><?= h($user->login_last_login_host) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Block Reason') ?></td>
            <td><?= h($user->block_reason) ?></td>
        </tr>


        <tr>
            <td><?= __d('user','Id') ?></td>
            <td><?= $this->Number->format($user->id) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Email Verification Expiry Timestamp') ?></td>
            <td><?= $this->Number->format($user->email_verification_expiry_timestamp) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Password Change Min Days') ?></td>
            <td><?= $this->Number->format($user->password_change_min_days) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Password Change Max Days') ?></td>
            <td><?= $this->Number->format($user->password_change_max_days) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Password Change Warning Days') ?></td>
            <td><?= $this->Number->format($user->password_change_warning_days) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Password Change Timestamp') ?></td>
            <td><?= $this->Number->format($user->password_change_timestamp) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Password Expiry Timestamp') ?></td>
            <td><?= $this->Number->format($user->password_expiry_timestamp) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Password Reset Expiry Timestamp') ?></td>
            <td><?= $this->Number->format($user->password_reset_expiry_timestamp) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Login Failure Count') ?></td>
            <td><?= $this->Number->format($user->login_failure_count) ?></td>
        </tr>


        <tr>
            <td><?= __d('user','Login Last Login Datetime') ?></td>
            <td><?= h($user->login_last_login_datetime) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Login Failure Datetime') ?></td>
            <td><?= h($user->login_failure_datetime) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Block Datetime') ?></td>
            <td><?= h($user->block_datetime) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Created') ?></td>
            <td><?= h($user->created) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Modified') ?></td>
            <td><?= h($user->modified) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __d('user','Email Verification Required') ?></td>
            <td><?= $user->email_verification_required ? __d('user','Yes') : __d('user','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('user','Email Verified') ?></td>
            <td><?= $user->email_verified ? __d('user','Yes') : __d('user','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('user','Password Force Change') ?></td>
            <td><?= $user->password_force_change ? __d('user','Yes') : __d('user','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('user','Login Enabled') ?></td>
            <td><?= $user->login_enabled ? __d('user','Yes') : __d('user','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('user','Block Enabled') ?></td>
            <td><?= $user->block_enabled ? __d('user','Yes') : __d('user','No'); ?></td>
        </tr>
    </table>
</div>
<div class="related">
    <div class="">
    <h4><?= __d('user','Related {0}', __d('user','Groups')) ?></h4>
    <?php if (!empty($user->groups)): ?>
    <table class="ui table">
        <tr>
            <th><?= __d('user','Id') ?></th>
            <th><?= __d('user','Name') ?></th>
            <th class="actions"><?= __d('user','Actions') ?></th>
        </tr>
        <?php foreach ($user->groups as $userGroups): ?>
        <tr>
            <td><?= h($userUserGroups->id) ?></td>
            <td><?= h($userUserGroups->name) ?></td>

            <td class="actions">
                <?= $this->Html->link(__d('user','View'), ['controller' => 'Groups', 'action' => 'view', $userUserGroups->id]) ?>

                <?= $this->Html->link(__d('user','Edit'), ['controller' => 'Groups', 'action' => 'edit', $userUserGroups->id]) ?>
            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
<?php debug($user); ?>
