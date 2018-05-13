<?php $this->Breadcrumbs->add(__d('user','Users'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('user','Edit {0}', __d('user','User'))); ?>
<div class="backend user">
    <?= $this->Form->create($user, ['horizontal' => true]); ?>
        <?php
        echo $this->Form->input('superuser');
        echo $this->Form->input('group_id', ['options' => $userGroups, 'empty' => true]);
        echo $this->Form->input('username');
        //echo $this->Form->input('password');
        echo $this->Form->input('name');
        echo $this->Form->input('email');
        echo $this->Form->input('email_verification_required');
        echo $this->Form->input('email_verification_code');
        //echo $this->Form->input('email_verification_expiry_timestamp');
        echo $this->Form->input('email_verified');
        echo $this->Form->input('password_change_min_days');
        echo $this->Form->input('password_change_max_days');
        echo $this->Form->input('password_change_warning_days');
        //echo $this->Form->input('password_change_timestamp');
        //echo $this->Form->input('password_expiry_timestamp');
        echo $this->Form->input('password_force_change');
        echo $this->Form->input('password_reset_code');
        //echo $this->Form->input('password_reset_expiry_timestamp');
        echo $this->Form->input('login_enabled');
        echo $this->Form->input('login_last_login_ip');
        echo $this->Form->input('login_last_login_host');
        //echo $this->Form->input('login_last_login_datetime');
        echo $this->Form->input('login_failure_count');
        //echo $this->Form->input('login_failure_datetime');
        echo $this->Form->input('block_enabled');
        echo $this->Form->input('block_reason');
        //echo $this->Form->input('block_datetime');
        //echo $this->Form->input('groups._ids', ['options' => $userGroups]);
        ?>
    <?= $this->Form->button(__d('user','Submit')) ?>
    <?= $this->Form->end() ?>
</div>
