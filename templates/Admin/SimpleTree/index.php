<?php $this->assign('title', __d('admin','SimpleTree Viewer')); ?>
<div class="index">

    <?= $this->cell('Admin.DataTable', [[
        'paginate' => false,
        'sortable' => ($this->get('sortUrl')),
        'model' => $this->get('modelName'),
        'data' => $this->get('data'),
        'fields' => [
            'id', 'refscope', 'refid', 'pos', 'title'
        ],

        'rowActions' => [
            [__d('admin','Move Up'), ['controller' => 'Posts', 'action' => 'moveUp', ':id'], ['class' => 'move up']],
            [__d('admin','Move Down'), ['controller' => 'Posts', 'action' => 'moveDown', ':id'], ['class' => 'move down']],
        ]
    ]]);
    ?>
</div>
<?php $this->Html->script('/admin/libs/jquery-ui/jquery-ui.min.js', ['block' => true]); ?>
