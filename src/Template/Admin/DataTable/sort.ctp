<?php $this->Html->script('/backend/libs/jquery-ui/jquery-ui.min.js', ['block' => true]); ?>
<div class="data-table data-table-sort">
    <?= $this->cell('Backend.DataTable', [[
        'paginate' => false,
        'sortable' => true,
        'sortUrl' => ['action' => 'tableSort'],
        'model' => $this->get('modelName'),
        'data' => $this->get('data'),
        'fields' => [
            'id',
            'pos',
            'refscope',
            'refid',
            'title'
        ],

        'rowActions' => [
            [__d('backend','Move Up'), ['controller' => 'Posts', 'action' => 'moveUp', ':id'], ['class' => 'move up']],
            [__d('backend','Move Down'), ['controller' => 'Posts', 'action' => 'moveDown', ':id'], ['class' => 'move down']],
        ]
    ]]);
    ?>
</div>