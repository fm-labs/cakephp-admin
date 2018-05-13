<?php $this->Breadcrumbs->add(__d('user','Users')); ?>
<?php $this->Toolbar->addLink(
    __d('user','New {0}', __d('user','User')),
    ['controller' => 'Users', 'action' => 'add'],
    ['data-icon' => 'plus']
); ?>
<?php $this->Toolbar->addLink(
    __d('user','List {0}', __d('user','User Groups')),
    ['controller' => 'UserGroups', 'action' => 'index'],
    ['data-icon' => 'list']
); ?>
<?php $this->Toolbar->addLink(
    __d('user','New {0}', __d('user','User Group')),
    ['controller' => 'UserGroups', 'action' => 'add'],
    ['data-icon' => 'plus']
); ?>
<div class="users index">

    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'User.Users',
        'data' => $users,
        'fields' => [
            'id',
            'login_enabled' => [
                'formatter' => function($val, $row) {
                    return $this->Ui->statusLabel($val);
                }
            ],
            'superuser' => [
                'formatter' => function($val, $row) {
                    return $this->Ui->statusLabel($val);
                }
            ],
            'username',
            'group_id' => [
                'formatter' => function($val, $row) {
                    $row->has('primary_group')
                        ? $this->Html->link($row->primary_group->name, ['controller' => 'UserGroups', 'action' => 'view', $row->primary_group->id])
                        : '';
                }
            ],
            'name',
            'email'
        ],
        'rowActions' => [
            [__d('user','View'), ['action' => 'view', ':id'], ['class' => 'view']],
            [__d('user','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('user','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('user','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>
    <?php debug($users); ?>
</div>
