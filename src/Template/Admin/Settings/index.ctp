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

    <table class="ui compact table">
        <thead>
        <tr>
            <th><?= $this->Paginator->sort('id'); ?></th>
            <th><?= $this->Paginator->sort('scope'); ?></th>
            <th><?= $this->Paginator->sort('ref'); ?></th>
            <th><?= $this->Paginator->sort('name'); ?></th>
            <th><?= $this->Paginator->sort('value'); ?></th>
            <th><?= $this->Paginator->sort('published'); ?></th>
            <th><?= __('Current Value'); ?></th>
            <th class="actions"><?= __('Actions'); ?></th>
        </tr>
        </thead>
    <?php foreach ($settings->toArray() as $setting): ?>
        <?php
        $currentValue = \Cake\Core\Configure::read($setting->name);
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
        ?>
        <tr>
            <td><?= h($setting->id) ?></td>
            <td><?= h($setting->scope) ?></td>
            <td><?= h($setting->ref) ?></td>
            <td><?= h($setting->name) ?></td>
            <td><?= h($setting->value) ?></td>
            <td><?= h($setting->published) ?></td>
            <td style="font-style: italic;"><?= h($currentValue); ?></td>
            <td class="actions">
                <div class="ui basic small buttons">
                    <?php if (isset($setting->id)): ?>
                        <div class="ui button">
                            <?= $this->Html->link(__d('backend','Edit'), ['action' => 'edit', $setting->id]) ?>
                        </div>
                        <div class="ui floating dropdown icon button">
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <?= $this->Ui->postLink(
                                    __d('backend','Reset to default'),
                                    ['action' => 'delete', $setting->id],
                                    ['class' => 'item', 'icon' => 'trash', 'confirm' => __d('backend','Are you sure you want to reset # {0}?', $key)]
                                ) ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="ui button">
                            <?= $this->Html->link(__d('backend','Add'), [
                                'action' => 'add',
                                'ref' => $setting->ref,
                                'key' => $key,
                                'type' => $setting->type,
                            ]) ?>
                        </div>
                        <div class="ui button">
                            <?= $this->Html->link(__d('backend','Edit'), [
                                'action' => 'add',
                                'ref' => $setting->ref,
                                'scope' => $setting->scope,
                                'name' => $setting->name,
                                'type' => $setting->type,
                            ]) ?>
                        </div>
                    <?php endif; ?>


                </div>
            </td>
        </tr>

    <?php endforeach; ?>
    </table>


    <?php debug($settings); ?>
</div>
