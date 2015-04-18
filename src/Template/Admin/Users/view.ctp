<?php $this->Html->addCrumb(__('Users'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($user->id); ?>
<div class="actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <div class="item">
                <i class="edit icon"></i>
                <?= $this->Html->link(__('Edit {0}', __('User')), ['action' => 'edit', $user->id]) ?>
            </div>
            <div class="item">
                <i class="remove icon"></i>
                <?= $this->Form->postLink(__('Delete {0}', __('User')), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?>
            </div>
            <div class="item">
                <i class="list icon"></i>
                <?= $this->Html->link(__('List {0}', __('Users')), ['action' => 'index']) ?>
            </div>
            <div class="item">
                <i class="add icon"></i>
                <?= $this->Html->link(__('New {0}', __('User')), ['action' => 'add']) ?>
            </div>
            <div class="ui item dropdown">
                <div class="menu">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="users view">
    <h2><?= h($user->id) ?></h2>
    <div class="ui list">

        <div class="item">
            <div class="content">
                <span class="header"><?= __('Username') ?></span>
                <div class="description"><?= h($user->username) ?></div>
            </div>
        </div>
        <div class="item">
            <div class="content">
                <span class="header"><?= __('Password') ?></span>
                <div class="description"><?= h($user->password) ?></div>
            </div>
        </div>


        <div class="item">
            <div class="content">
                <span class="header"><?= __('Id') ?></span>
                <div class="description"><?= $this->Number->format($user->id) ?></div>
            </div>
        </div>


            <div class="item">
                <div class="content">
                    <span class="header"><?= __('Created') ?></span>
                    <div class="description"><?= h($user->created) ?></div>
                </div>
            </div>
            <div class="item">
                <div class="content">
                    <span class="header"><?= __('Modified') ?></span>
                    <div class="description"><?= h($user->modified) ?></div>
                </div>
            </div>

        <div class="booleans">
            <div class="item">
                <div class="content">
                    <span class="header"><?= __('Is Login Allowed') ?></span>
                    <div class="description"><?= $user->is_login_allowed ? __('Yes') : __('No'); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
