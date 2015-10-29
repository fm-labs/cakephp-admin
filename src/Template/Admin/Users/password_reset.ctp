<?php $this->Html->addCrumb(__('Users'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Reset password')); ?>
<div id="user-change-password-form">
    <?= $this->Form->create($user, ['class' => 'ui form']); ?>
    <h2 class="ui top attached header">
        <?= __('Set a new password for user {0} (ID: {1})', $user->username, $user->id); ?>
    </h2>
    <div class="ui attached segment">
    <?= $this->Form->input('password1', [
        'label' => __('New password'),
        'type' => 'password',
        'required' => true,
        'default' => '',
    ]); ?>
    <?= $this->Form->input('password2', [
        'label' => __('Repeat password'),
        'type' => 'password',
        'required' => true,
        'default' => '',
    ]); ?>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->submit(__('Update password now')); ?>
    </div>
    <?= $this->Form->end(); ?>
</div>