<?php $this->Breadcrumbs->add(__d('user','Users'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('user','Reset password')); ?>
<?php $this->Toolbar->addLink(__d('user','Back to user'), ['action' => 'view', $user->id], ['data-icon' => 'chevron-left']); ?>
<div id="user-change-password-form">
    <?= $this->Form->create($user, ['class' => '']); ?>
    <h2>
        <?= __d('user','Set a new password for user {0} (ID: {1})', $user->username, $user->id); ?>
    </h2>
    <?= $this->Form->input('password1', [
        'label' => __d('user','New password'),
        'type' => 'password',
        'required' => true,
        'default' => '',
    ]); ?>
    <?= $this->Form->input('password2', [
        'label' => __d('user','Repeat password'),
        'type' => 'password',
        'required' => true,
        'default' => '',
    ]); ?>
    <?= $this->Form->submit(__d('user','Update password now')); ?>
    <?= $this->Form->end(); ?>
</div>