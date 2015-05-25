<?php $this->Html->addCrumb(__('Users'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('New {0}', __('User'))); ?>
<div class="users">
    <div class="actions">
        <div class="ui secondary menu">
            <div class="item"></div>
            <div class="right menu">
                    <?= $this->Ui->link(
                    __('List {0}', __('Users')),
                    ['action' => 'index'],
                    ['class' => 'item', 'icon' => 'list']
                ) ?>
                <div class="ui dropdown item">
                    <i class="dropdown icon"></i>
                    <i class="tasks icon"></i>Actions
                    <div class="menu">
    
                        <?= $this->Ui->link(
                            __('List {0}', __('User Groups')),
                            ['controller' => 'UserGroups', 'action' => 'index'],
                            ['class' => 'item', 'icon' => 'list']
                        ) ?>

                        <?= $this->Ui->link(
                            __('New {0}', __('User Group')),
                            ['controller' => 'UserGroups', 'action' => 'add'],
                            ['class' => 'item', 'icon' => 'add']
                        ) ?>
                            </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui divider"></div>

    <?= $this->Form->create($user); ?>
    <h2 class="ui top attached header">
        <?= __('Add {0}', __('User')) ?>
    </h2>
    <div class="users ui attached segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('user_group_id', ['options' => $primaryUserGroup, 'empty' => true]);
                echo $this->Form->input('username');
                echo $this->Form->input('password');
                echo $this->Form->input('name', ['label' => __('Real name')]);
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
        <?= $this->Form->button(__('Submit')) ?>
    </div>
    <?= $this->Form->end() ?>
    <?php debug($user->errors()); ?>
</div>