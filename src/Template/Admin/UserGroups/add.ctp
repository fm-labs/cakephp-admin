<?php $this->Breadcrumbs->add(__d('user','User Groups'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('user','New {0}', __d('user','User Group'))); ?>
<div class="userGroups">
    <?= $this->Form->create($userGroup); ?>
    <h2 class="ui top attached header">
        <?= __d('user','Add {0}', __d('user','User Group')) ?>
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
        <?= $this->Form->button(__d('user','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>