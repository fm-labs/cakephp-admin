<?php $this->Html->addCrumb(__('User Groups'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('New {0}', __('User Group'))); ?>
<div class="userGroups">
    <?= $this->Form->create($userGroup); ?>
    <h2 class="ui top attached header">
        <?= __('Add {0}', __('User Group')) ?>
    </h2>
    <div class="users ui attached segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('name');
                //echo $this->Form->input('password');
                //echo $this->Form->input('users._ids', ['options' => $users]);
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__('Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>