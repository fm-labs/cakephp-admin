<?php $this->Breadcrumbs->add(__d('user','User Groups'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($userGroup->name); ?>
<div class="actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __d('user','Edit {0}', __d('user','User Group')),
                ['action' => 'edit', $userGroup->id],
                ['class' => 'item', 'data-icon' => 'edit']
            ) ?>
            <?= $this->Ui->postLink(
                __d('user','Delete {0}', __d('user','User Group')),
                ['action' => 'delete', $userGroup->id],
                ['class' => 'item', 'data-icon' => 'trash', 'confirm' => __d('user','Are you sure you want to delete # {0}?', $userGroup->id)]) ?>

            <?= $this->Ui->link(
                __d('user','List {0}', __d('user','User Groups')),
                ['action' => 'index'],
                ['class' => 'item', 'data-icon' => 'list']
            ) ?>
            <?= $this->Ui->link(
                __d('user','New {0}', __d('user','User Group')),
                ['action' => 'add'],
                ['class' => 'item', 'data-icon' => 'plus']
            ) ?>
            <div class="ui item dropdown">
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

<div class="userGroups view">
    <h2 class="ui top attached header">
        <?= h($userGroup->name) ?>
    </h2>
    <table class="ui attached celled striped table">
        <!--
        <thead>
        <tr>
            <th><?= __d('user','Label'); ?></th>
            <th><?= __d('user','Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __d('user','Name') ?></td>
            <td><?= h($userGroup->name) ?></td>
        </tr>
        <tr>
            <td><?= __d('user','Password') ?></td>
            <td><?= h($userGroup->password) ?></td>
        </tr>


        <tr>
            <td><?= __d('user','Id') ?></td>
            <td><?= $this->Number->format($userGroup->id) ?></td>
        </tr>

    </table>
</div>
<div class="related">
    <div class="">
    <h4><?= __d('user','Related {0}', __d('user','Primary Users')) ?></h4>
    <?php if (!empty($userGroup->primary_users)): ?>
    <table class="ui table">
        <thead>
        <tr>
            <th><?= __d('user','Id') ?></th>
            <th><?= __d('user','Username') ?></th>
            <th><?= __d('user','Email') ?></th>
            <th class="actions"><?= __d('user','Actions') ?></th>
        </tr>
        </thead>
        <?php foreach ($userGroup->primary_users as $primaryUsers): ?>
        <tr>
            <td><?= h($primaryUsers->id) ?></td>
            <td><?= h($primaryUsers->username) ?></td>
            <td><?= h($primaryUsers->email) ?></td>
            <td class="actions">
                <?= $this->Html->link(__d('user','View'), ['controller' => 'Users', 'action' => 'view', $primaryUsers->id]) ?>
                <?= $this->Html->link(__d('user','Edit'), ['controller' => 'Users', 'action' => 'edit', $primaryUsers->id]) ?>
            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
<div class="related">
    <div class="">
    <h4><?= __d('user','Related {0}', __d('user','Users')) ?></h4>
    <?php if (!empty($userGroup->users)): ?>
    <table class="ui table">
        <thead>
        <tr>
            <th><?= __d('user','Id') ?></th>
            <th><?= __d('user','Username') ?></th>
            <th><?= __d('user','Email') ?></th>
            <th class="actions"><?= __d('user','Actions') ?></th>
        </tr>
        </thead>
        <?php foreach ($userGroup->users as $users): ?>
        <tr>
            <td><?= h($users->id) ?></td>
            <td><?= h($users->username) ?></td>
            <td><?= h($users->email) ?></td>
            <td class="actions">
                <?= $this->Html->link(__d('user','View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                <?= $this->Html->link(__d('user','Edit'), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?>
            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
