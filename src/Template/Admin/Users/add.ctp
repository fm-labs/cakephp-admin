<?php $this->Html->addCrumb(__('Users'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('New {0}', __('User'))); ?>
<div class="actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <div class="item">
                <i class="list icon"></i>
                <?= $this->Html->link(__('List {0}', __('Users')), ['action' => 'index']) ?>
            </div>
            <div class="ui dropdown item">
                <i class="dropdown icon"></i>
                <i class="tasks icon"></i>Actions
                <div class="menu">
                    <div class="item">No Actions</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="users ui form">
    <h2><?= __('Add {0}', __('User')) ?></h2>
    <?= $this->Form->create($user); ?>
    <?php
        echo $this->Form->input('username');
        echo $this->Form->input('password1');
        echo $this->Form->input('password2');
        echo $this->Form->input('is_login_allowed');
    ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<?php debug($user); ?>