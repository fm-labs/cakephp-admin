<?php $this->Html->addCrumb(__('User Groups'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('User Group'))); ?>
<div class="userGroups">
    <div class="actions">
        <div class="ui secondary menu">
            <div class="item"></div>
            <div class="right menu">
                <?= $this->Ui->postLink(
                __('Delete'),
                ['action' => 'delete', $userGroup->id],
                ['class' => 'item', 'icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $userGroup->id)]
            )
            ?>
                    <?= $this->Ui->link(
                    __('List {0}', __('User Groups')),
                    ['action' => 'index'],
                    ['class' => 'item', 'icon' => 'list']
                ) ?>
                <div class="ui dropdown item">
                    <i class="dropdown icon"></i>
                    <i class="tasks icon"></i>Actions
                    <div class="menu">
    
                        <?= $this->Ui->link(
                            __('List {0}', __('Users')),
                            ['controller' => 'Users', 'action' => 'index'],
                            ['class' => 'item', 'icon' => 'list']
                        ) ?>

                        <?= $this->Ui->link(
                            __('New {0}', __('User')),
                            ['controller' => 'Users', 'action' => 'add'],
                            ['class' => 'item', 'icon' => 'add']
                        ) ?>
                            </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui divider"></div>

    <?= $this->Form->create($userGroup); ?>
    <h2 class="ui top attached header">
        <?= __('Edit {0}', __('User Group')) ?>
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