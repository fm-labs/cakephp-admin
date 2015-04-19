<?php $this->Html->addCrumb(__('Users'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Change my password')); ?>

<div class="actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __('New {0}', __('User')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>

            <?= $this->Ui->link(__('Back'), '/', [
                'onclick' => "javascript:history.go(-1)",
                'class' => 'item',
                'icon' => 'arrow left'
            ]); ?>
        </div>
    </div>
</div>
<div class="ui divider"></div>
<div id="user-change-password-form">
    <?= $this->Form->create($user, ['class' => 'ui form']); ?>
    <h2 class="ui top attached header">
        <?= __('Create a new password'); ?>
    </h2>
    <div class="ui attached segment">
    <?= $this->Form->input('password0', [
        'label' => __('Current password'),
        'type' => 'password',
        'required' => true
    ]); ?>
    <?= $this->Form->input('password1', [
        'label' => __('New password'),
        'type' => 'password',
        'required' => true
    ]); ?>
    <?= $this->Form->input('password2', [
        'label' => __('Repeat password'),
        'type' => 'password',
        'required' => true
    ]); ?>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->submit(__('Change my password now')); ?>
    </div>
    <?= $this->Form->end(); ?>
</div>