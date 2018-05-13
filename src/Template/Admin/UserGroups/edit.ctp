<?php $this->Breadcrumbs->add(__d('user','User Groups'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('user','Edit {0}', __d('user','User Group'))); ?>
<div class="userGroups">
    <div class="actions">
        <div class="ui secondary menu">
            <div class="item"></div>
            <div class="right menu">
                <?= $this->Ui->postLink(
                __d('user','Delete'),
                ['action' => 'delete', $userGroup->id],
                ['class' => 'item', 'data-icon' => 'trash', 'confirm' => __d('user','Are you sure you want to delete # {0}?', $userGroup->id)]
            )
            ?>
                    <?= $this->Ui->link(
                    __d('user','List {0}', __d('user','User Groups')),
                    ['action' => 'index'],
                    ['class' => 'item', 'data-icon' => 'list']
                ) ?>
                <div class="ui dropdown item">
                    <i class="dropdown icon"></i>
                    <i class="tasks icon"></i>Actions
                    <div class="menu">
    
                        <?= $this->Ui->link(
                            __d('user','List {0}', __d('user','Users')),
                            ['controller' => 'Users', 'action' => 'index'],
                            ['class' => 'item', 'data-icon' => 'list']
                        ) ?>

                        <?= $this->Ui->link(
                            __d('user','New {0}', __d('user','User')),
                            ['controller' => 'Users', 'action' => 'add'],
                            ['class' => 'item', 'data-icon' => 'plus']
                        ) ?>
                            </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui divider"></div>

    <?= $this->Form->create($userGroup); ?>
    <h2 class="ui top attached header">
        <?= __d('user','Edit {0}', __d('user','User Group')) ?>
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