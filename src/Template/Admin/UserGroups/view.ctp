<?php $this->Html->addCrumb(__('User Groups'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($userGroup->name); ?>
<div class="actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __('Edit {0}', __('User Group')),
                ['action' => 'edit', $userGroup->id],
                ['class' => 'item', 'icon' => 'edit']
            ) ?>
            <?= $this->Ui->postLink(
                __('Delete {0}', __('User Group')),
                ['action' => 'delete', $userGroup->id],
                ['class' => 'item', 'icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $userGroup->id)]) ?>

            <?= $this->Ui->link(
                __('List {0}', __('User Groups')),
                ['action' => 'index'],
                ['class' => 'item', 'icon' => 'list']
            ) ?>
            <?= $this->Ui->link(
                __('New {0}', __('User Group')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'add']
            ) ?>
            <div class="ui item dropdown">
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

<div class="userGroups view">
    <h2 class="ui top attached header">
        <?= h($userGroup->name) ?>
    </h2>
    <table class="ui attached celled striped table">
        <!--
        <thead>
        <tr>
            <th><?= __('Label'); ?></th>
            <th><?= __('Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __('Name') ?></td>
            <td><?= h($userGroup->name) ?></td>
        </tr>
        <tr>
            <td><?= __('Password') ?></td>
            <td><?= h($userGroup->password) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($userGroup->id) ?></td>
        </tr>

    </table>
</div>
<div class="related">
    <div class="">
    <h4><?= __('Related {0}', __('Primary Users')) ?></h4>
    <?php if (!empty($userGroup->primary_users)): ?>
    <table class="ui table">
        <thead>
        <tr>
            <th><?= __('Id') ?></th>
            <th><?= __('Username') ?></th>
            <th><?= __('Email') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        </thead>
        <?php foreach ($userGroup->primary_users as $primaryUsers): ?>
        <tr>
            <td><?= h($primaryUsers->id) ?></td>
            <td><?= h($primaryUsers->username) ?></td>
            <td><?= h($primaryUsers->email) ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $primaryUsers->id]) ?>
                <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $primaryUsers->id]) ?>
            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
<div class="related">
    <div class="">
    <h4><?= __('Related {0}', __('Users')) ?></h4>
    <?php if (!empty($userGroup->users)): ?>
    <table class="ui table">
        <thead>
        <tr>
            <th><?= __('Id') ?></th>
            <th><?= __('Username') ?></th>
            <th><?= __('Email') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        </thead>
        <?php foreach ($userGroup->users as $users): ?>
        <tr>
            <td><?= h($users->id) ?></td>
            <td><?= h($users->username) ?></td>
            <td><?= h($users->email) ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?>
            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
