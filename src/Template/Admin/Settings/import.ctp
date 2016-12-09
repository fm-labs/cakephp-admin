<?php $this->Breadcrumbs->add(__d('backend','Settings'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend','Settings Import')); ?>
<?= $this->Toolbar->addLink(
    __d('backend','List {0}', __d('backend','Settings')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('backend','New {0}', __d('backend','Setting')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<div class="settings index">
    <h1>Settings Import</h1>

    <table class="ui compact table">
        <thead>
        <tr>
            <th>Scope</th>
            <th>Schema</th>
            <th>Path</th>
            <th>Imported</th>
            <th class="actions">Actions</th>
        </tr>
        </thead>
        <?php foreach ($imports as $import): ?>
        <tr>
            <td>[<?= h($import['scope']) ?>]</td>
            <td><?= h($import['ref']) ?></td>
            <td><?= h($import['path']) ?></td>
            <td><?= h($import['imported']) ?></td>
            <td>
                <?= $this->Html->link('Import', ['action' => 'import', $import['ref'], $import['scope']]); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php debug($this->get('settings')); ?>
</div>