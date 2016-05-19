<?php $this->Html->addCrumb(__d('backend','Settings')); ?>
<?= $this->Toolbar->addLink(
    __d('backend','New {0}', __d('backend','Setting')),
    ['action' => 'add'],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __d('backend','Import {0}', __d('backend','Settings')),
    ['action' => 'import'],
    ['icon' => 'download']
) ?>
<?= $this->Toolbar->addLink(
    __d('backend','Dump {0}', __d('backend','Settings')),
    ['action' => 'dump'],
    ['icon' => 'arrow down']
) ?>

<div class="settings index">

    <h2>Settings</h2>


    <?= $this->cell('Backend.DataTable', [[
        'paginate' => false,
        'model' => 'Settings.Settings',
        'data' => $settings,
        'fields' => [
            'id',
            'scope',
            'ref',
            'name' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['action' => 'edit', $row->id]);
                }
            ],
            'value_type',
            'value',
            'current_value' => [
                'formatter' => function($val, $entity) {

                    $currentValue = \Cake\Core\Configure::read($entity->name);
                    switch (true) {
                        case is_array($currentValue):
                            $currentValue = print_r($currentValue, true);
                            break;
                        case is_object($currentValue):
                            $currentValue = (string) $currentValue;
                            break;
                        case is_resource($currentValue):
                        case is_callable($currentValue):
                            $currentValue = "[" . gettype($currentValue) . "]";
                            break;
                    }
                    return $currentValue;
                }
            ],
            'is_published'
        ],
        'rowActions' => [
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Reset'), ['action' => 'reset', ':id'], ['class' => 'reset', 'confirm' => __d('shop','Are you sure you want to reset # {0}?', ':name')]],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':name')]]
        ]
    ]]);
    ?>

    <?php debug($settings); ?>
</div>
