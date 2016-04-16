<?php $this->Html->addCrumb(__d('banana','Settings')); ?>
<div class="be-toolbar actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __d('banana','New {0}', __d('banana','Setting')),
                ['action' => 'add'],
                ['class' => 'item', 'icon' => 'plus']
            ) ?>
            <div class="ui dropdown item">
                <i class="dropdown icon"></i>
                <i class="setting icon"></i>Actions
                <div class="menu">
                    <div class="item">No Actions</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="settings index">

    <h2>Manage Settings</h2>

    <?php foreach ($compiledSettings as $scopeKey => $scopeSettings): ?>
        <h3><?= h($scopeKey); ?></h3>
        <table class="ui table striped">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th><?= h('key') ?></th>
                <th><?= h('scope') ?></th>
                <th><?= h('name') ?></th>
                <th><?= h('value') ?></th>
                <th><?= h('default') ?></th>
                <th class="actions"><?= __d('banana','Actions') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($scopeSettings as $key => $setting): ?>
            <tr>
                <td>&nbsp;</td>
                <td><?= h($key) ?></td>
                <td><?= h($setting->scope) ?></td>
                <td><?= h($setting->name) ?></td>
                <td><?= h($setting->value) ?></td>
                <td><?= h($setting->default) ?></td>
                <td class="actions">
                    <div class="ui basic small buttons">
                        <?php if (isset($setting->id)): ?>
                            <div class="ui button">
                                <?= $this->Html->link(__d('banana','Edit'), ['action' => 'edit', $setting->id]) ?>
                            </div>
                            <div class="ui floating dropdown icon button">
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    <?= $this->Ui->postLink(
                                        __d('banana','Reset to default'),
                                        ['action' => 'delete', $setting->id],
                                        ['class' => 'item', 'icon' => 'trash', 'confirm' => __d('banana','Are you sure you want to reset # {0}?', $key)]
                                    ) ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="ui button">
                                <?= $this->Html->link(__d('banana','Add'), [
                                    'action' => 'add',
                                    'ref' => $setting->ref,
                                    'key' => $key,
                                    'type' => $setting->type,
                                ]) ?>
                            </div>
                            <div class="ui button">
                                <?= $this->Html->link(__d('banana','Edit'), [
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

            <?php
            if (isset($dbSettingsKeys[$key])) {
                unset($dbSettingsKeys[$key]);
            }
            ?>
        <?php endforeach; ?>
        </tbody>
        </table>

    <?php endforeach; ?>

    <h2>Custom Settings</h2>
    <table class="ui table striped">
        <thead>
            <tr>
                <th>Key</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dbSettingsKeys as $dbSettingsKey => $dbSettingId): ?>
            <tr>
                <td><?= $this->Html->link($dbSettingsKey, ['action' => 'edit', $dbSettingId]); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <?php debug($compiledSettings); ?>
    <?php debug($dbSettingsKeys); ?>
</div>
