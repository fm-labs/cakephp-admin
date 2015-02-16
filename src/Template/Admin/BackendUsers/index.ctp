<?php $this->Html->addCrumb(__('Backend Users')); ?>
<div class="actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <div class="item">
                <i class="add icon"></i>
                <?= $this->Html->link(__('New Backend User'), ['action' => 'add']) ?>
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

<div class="backendUsers index">
    <table class="ui table striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('first_name') ?></th>
            <th><?= $this->Paginator->sort('last_name') ?></th>
            <th><?= $this->Paginator->sort('username') ?></th>
            <th><?= $this->Paginator->sort('password') ?></th>
            <th><?= $this->Paginator->sort('email') ?></th>
            <th><?= $this->Paginator->sort('active') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($backendUsers as $backendUser): ?>
        <tr>
            <td><?= $this->Number->format($backendUser->id) ?></td>
            <td><?= h($backendUser->first_name) ?></td>
            <td><?= h($backendUser->last_name) ?></td>
            <td><?= h($backendUser->username) ?></td>
            <td><?= h($backendUser->password) ?></td>
            <td><?= h($backendUser->email) ?></td>
            <td><?= h($backendUser->active) ?></td>
            <td class="actions">
                <div class="ui basic mini buttons">
                    <div class="ui button">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $backendUser->id]) ?>
                    </div>
                    <div class="ui floating dropdown icon button">
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <div class="item"><i class="edit icon"></i>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $backendUser->id]) ?>
                            </div>
                            <div class="item"><i class="delete icon"></i>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['action' => 'delete', $backendUser->id],
                                    ['confirm' => __('Are you sure you want to delete # {0}?', $backendUser->id)]
                                ) ?>
                            </div>
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
