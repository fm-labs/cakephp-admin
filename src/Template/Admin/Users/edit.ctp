<?php $this->Html->addCrumb(__('Users'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('User'))); ?>
<div class="actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <div class="item">
                <i class="remove icon"></i>
                <?= $this->Form->postLink(
                    __('Delete'),
                    ['action' => 'delete', $user->id],
                    ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]
                )
                ?>
            </div>
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
    <h2><?= __('Edit {0}', __('User')) ?></h2>
    <?= $this->Form->create($user); ?>
    <?php
        echo $this->Form->input('username');
        //echo $this->Form->input('password');
        echo $this->Form->input('is_login_allowed');
    ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
