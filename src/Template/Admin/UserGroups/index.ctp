<?php $this->Html->addCrumb(__('User Groups')); ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('User Group')),
    ['action' => 'add'],
    ['icon' => 'add']
) ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Users')),
    ['controller' => 'Users', 'action' => 'index'],
    ['icon' => 'list']
); ?>
<div class="userGroups index">
    <table class="ui table striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('name') ?></th>
            <th><?= $this->Paginator->sort('password') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($userGroups as $userGroup): ?>
        <tr>
            <td><?= $this->Number->format($userGroup->id) ?></td>
            <td><?= h($userGroup->name) ?></td>
            <td><?= h($userGroup->password) ?></td>
            <td class="actions">
                <div class="ui basic small buttons">
                    <div class="ui button">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $userGroup->id]) ?>
                    </div>
                    <div class="ui floating dropdown icon button">
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <?= $this->Ui->link(
                                __('Edit'),
                                ['action' => 'edit', $userGroup->id],
                                ['class' => 'item', 'icon' => 'edit']
                            ) ?>
                            <?= $this->Ui->postLink(
                                __('Delete'),
                                ['action' => 'delete', $userGroup->id],
                                ['class' => 'item', 'icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $userGroup->id)]
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
</div>
