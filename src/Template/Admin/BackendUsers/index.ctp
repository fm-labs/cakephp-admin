<?php $this->Html->addCrumb(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Backend Users')); ?>

<h2><?= _('Backend Users'); ?></h2>

<div class="users index">
    <table class="ui table striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('username') ?></th>
            <th><?= $this->Paginator->sort('created') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $this->Number->format($user->id) ?></td>
            <td><?= h($user->username) ?></td>
            <td><?= h($user->created) ?></td>
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
