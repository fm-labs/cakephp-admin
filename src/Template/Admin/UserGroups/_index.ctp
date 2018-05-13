<?php $this->Breadcrumbs->add(__d('user','User Groups')); ?>
<?php $this->Toolbar->addLink(
    __d('user','New {0}', __d('user','User Group')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('user','List {0}', __d('user','Users')),
    ['controller' => 'Users', 'action' => 'index'],
    ['data-icon' => 'list']
); ?>
<div class="userGroups index">
    <table class="ui table compact striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('name') ?></th>
            <th><?= $this->Paginator->sort('password') ?></th>
            <th class="actions"><?= __d('user','Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($userGroups as $userGroup): ?>
        <tr>
            <td><?= $this->Number->format($userGroup->id) ?></td>
            <td><?= h($userGroup->name) ?></td>
            <td><?= h($userGroup->password) ?></td>
            <td class="actions">
                <div class="ui basic tiny buttons">
                    <div class="ui button">
                        <?= $this->Html->link(__d('user','View'), ['action' => 'view', $userGroup->id]) ?>
                    </div>
                    <div class="ui floating dropdown icon button">
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <?= $this->Ui->link(
                                __d('user','Edit'),
                                ['action' => 'edit', $userGroup->id],
                                ['class' => 'item', 'data-icon' => 'edit']
                            ) ?>
                            <?= $this->Ui->postLink(
                                __d('user','Delete'),
                                ['action' => 'delete', $userGroup->id],
                                ['class' => 'item', 'data-icon' => 'trash', 'confirm' => __d('user','Are you sure you want to delete # {0}?', $userGroup->id)]
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
            <?= $this->Paginator->prev(__d('user','previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__d('user','next')) ?>

            <div class="item">
                <?= $this->Paginator->counter() ?>
            </div>
        </div>
    </div>
</div>
