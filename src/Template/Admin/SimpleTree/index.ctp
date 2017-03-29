<?php $this->assign('title', __('SimpleTree Viewer')); ?>
<div class="index">

    <?= $this->cell('Backend.DataTable', [[
        'paginate' => false,
        'sortable' => ($this->get('sortUrl')),
        'model' => $this->get('modelName'),
        'data' => $this->get('data'),
        'fields' => [
            'id', 'refscope', 'refid', 'pos', 'title'
        ],

        'rowActions' => [
            [__d('shop','Move Up'), ['controller' => 'Posts', 'action' => 'moveUp', ':id'], ['class' => 'move up']],
            [__d('shop','Move Down'), ['controller' => 'Posts', 'action' => 'moveDown', ':id'], ['class' => 'move down']],
        ]
    ]]);
    ?>
</div>
<?php $this->Html->script('/backend/libs/jquery-ui/jquery-ui.min.js', ['block' => true]); ?>
