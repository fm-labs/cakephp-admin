<?php $this->Html->addCrumb(__('Backend Users'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($backendUser->id); ?>
<div class="actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <div class="item">
                <i class="edit icon"></i>
                <?= $this->Html->link(__('Edit Backend User'), ['action' => 'edit', $backendUser->id]) ?>
            </div>
            <div class="item">
                <i class="remove icon"></i>
                <?= $this->Form->postLink(__('Delete Backend User'), ['action' => 'delete', $backendUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $backendUser->id)]) ?>
            </div>
            <div class="item">
                <i class="list icon"></i>
                <?= $this->Html->link(__('List Backend Users'), ['action' => 'index']) ?>
            </div>
            <div class="item">
                <i class="add icon"></i>
                <?= $this->Html->link(__('New Backend User'), ['action' => 'add']) ?>
            </div>
            <div class="ui item dropdown">
                <div class="menu">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="backendUsers view">
    <h2><?= h($backendUser->id) ?></h2>
    <div class="ui list">

        <div class="item">
            <div class="content">
                <span class="header"><?= __('First Name') ?></span>
                <div class="description"><?= h($backendUser->first_name) ?></div>
            </div>
        </div>
        <div class="item">
            <div class="content">
                <span class="header"><?= __('Last Name') ?></span>
                <div class="description"><?= h($backendUser->last_name) ?></div>
            </div>
        </div>
        <div class="item">
            <div class="content">
                <span class="header"><?= __('Username') ?></span>
                <div class="description"><?= h($backendUser->username) ?></div>
            </div>
        </div>
        <div class="item">
            <div class="content">
                <span class="header"><?= __('Password') ?></span>
                <div class="description"><?= h($backendUser->password) ?></div>
            </div>
        </div>
        <div class="item">
            <div class="content">
                <span class="header"><?= __('Email') ?></span>
                <div class="description"><?= h($backendUser->email) ?></div>
            </div>
        </div>


        <div class="item">
            <div class="content">
                <span class="header"><?= __('Id') ?></span>
                <div class="description"><?= $this->Number->format($backendUser->id) ?></div>
            </div>
        </div>


            <div class="item">
                <div class="content">
                    <span class="header"><?= __('Last Login Datetime') ?></span>
                    <div class="description"><?= h($backendUser->last_login_datetime) ?></div>
                </div>
            </div>
            <div class="item">
                <div class="content">
                    <span class="header"><?= __('Created') ?></span>
                    <div class="description"><?= h($backendUser->created) ?></div>
                </div>
            </div>
            <div class="item">
                <div class="content">
                    <span class="header"><?= __('Modified') ?></span>
                    <div class="description"><?= h($backendUser->modified) ?></div>
                </div>
            </div>

        <div class="booleans">
            <div class="item">
                <div class="content">
                    <span class="header"><?= __('Active') ?></span>
                    <div class="description"><?= $backendUser->active ? __('Yes') : __('No'); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
