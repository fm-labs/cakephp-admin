<?php $this->Html->addCrumb(__('Users')); ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('User')),
    ['controller' => 'Users', 'action' => 'add'],
    ['icon' => 'add']
); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('User Groups')),
    ['controller' => 'UserGroups', 'action' => 'index'],
    ['icon' => 'list']
); ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('User Group')),
    ['controller' => 'UserGroups', 'action' => 'add'],
    ['icon' => 'add']
); ?>
<div class="users index">
    <table class="ui table striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('superuser') ?></th>
            <th><?= $this->Paginator->sort('username') ?></th>
            <th><?= $this->Paginator->sort('group_id') ?></th>
            <th><?= $this->Paginator->sort('name') ?></th>
            <th><?= $this->Paginator->sort('email') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $this->Number->format($user->id) ?></td>
            <td><?= $this->Ui->statusLabel($user->is_superuser) ?></td>
            <td><?= h($user->username) ?></td>
            <td>
                <?= $user->has('primary_group') ? $this->Html->link($user->primary_group->name, ['controller' => 'UserGroups', 'action' => 'view', $user->primary_group->id]) : '' ?>
            </td>
            <td><?= h($user->name) ?></td>
            <td><?= h($user->email) ?></td>
            <td class="actions">
                <div class="ui basic small buttons">
                    <div class="ui button">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?>
                    </div>
                    <div class="ui floating dropdown icon button">
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <?= $this->Ui->link(
                                __('Edit'),
                                ['action' => 'edit', $user->id],
                                ['class' => 'item', 'icon' => 'edit']
                            ) ?>
                            <?= $this->Ui->postLink(
                                __('Delete'),
                                ['action' => 'delete', $user->id],
                                ['class' => 'item', 'icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $user->id)]
                            ) ?>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
    </table>
    <div class="paginator">
        <div class="ui pagination menu">
            <?= $this->Paginator->prev(__('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next')) ?>

            <div class="item">
                <?= $this->Paginator->counter() ?>
            </div>
        </div>
    </div>

    <?php debug($users); ?>
</div>
