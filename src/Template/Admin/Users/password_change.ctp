<?php $this->Breadcrumbs->add(__d('user','Users'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('user','Change my password')); ?>
<?php $this->assign('title', __d('user','Change password')); ?>
<?php $this->Toolbar->addLink(__d('user','Back to user'), ['action' => 'view', $user->id], ['data-icon' => 'chevron-left']); ?>
<div id="user-change-password-form">
    <?= $this->Form->create($user, ['class' => 'ui form']); ?>
    <h2 class="ui header">
        <?= __d('user','Change password'); ?>
    </h2>
    <div class="ui top attached segment">
    <?= $this->Form->input('password0', [
        'label' => __d('user','Current password'),
        'type' => 'password',
        'required' => true
    ]); ?>
    <?= $this->Form->input('password1', [
        'label' => __d('user','New password'),
        'type' => 'password',
        'required' => true
    ]); ?>
    <?= $this->Form->input('password2', [
        'label' => __d('user','Repeat password'),
        'type' => 'password',
        'required' => true
    ]); ?>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->submit(__d('user','Change password now')); ?>
    </div>
    <?= $this->Form->end(); ?>
</div>