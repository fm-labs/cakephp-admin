<?php $this->Breadcrumbs->add(__d('user','Users'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('user','New {0}', __d('user','User'))); ?>
<?php $this->Toolbar->addLink(
    __d('user','List {0}', __d('user','Users')),
    ['controller' => 'Users', 'action' => 'index'],
    ['data-icon' => 'list']
); ?>
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
<div class="users">

    <h2 class="ui header">
        <?= __d('user','Add {0}', __d('user','User')) ?>
    </h2>
    <?= $this->Form->create($user); ?>
    <div class="users ui attached basic segment">
        <div class="ui form">
        <?php
        echo $this->Form->input('superuser');
        echo $this->Form->input('group_id', ['options' => $primaryGroup, 'empty' => true]);
        echo $this->Form->input('username');
        echo $this->Form->input('name', ['label' => __d('user','Real name')]);
        echo $this->Form->input('password1', ['type' => 'password']);
        echo $this->Form->input('password2', ['type' => 'password']);
        echo $this->Form->input('email');
        //echo $this->Form->input('email_verification_required');
        //echo $this->Form->input('email_verification_code');
        //echo $this->Form->input('email_verification_expiry_timestamp');
        //echo $this->Form->input('email_verified');
        //echo $this->Form->input('password_change_min_days');
        //echo $this->Form->input('password_change_max_days');
        //echo $this->Form->input('password_change_warning_days');
        //echo $this->Form->input('password_change_timestamp');
        //echo $this->Form->input('password_expiry_timestamp');
        //echo $this->Form->input('password_force_change');
        //echo $this->Form->input('password_reset_code');
        //echo $this->Form->input('password_reset_expiry_timestamp');
        //echo $this->Form->input('login_enabled');
        //echo $this->Form->input('login_last_login_ip');
        //echo $this->Form->input('login_last_login_host');
        //echo $this->Form->input('login_last_login_datetime');
        //echo $this->Form->input('login_failure_count');
        //echo $this->Form->input('login_failure_datetime');
        //echo $this->Form->input('block_enabled');
        //echo $this->Form->input('block_reason');
        //echo $this->Form->input('block_datetime');
        //echo $this->Form->input('user_groups._ids', ['options' => $userGroups]);
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('user','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>
    <?php debug($user->errors()); ?>
</div>