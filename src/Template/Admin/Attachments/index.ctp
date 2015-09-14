<?php $this->Html->addCrumb(__('Attachments')); ?>

<?php $this->Toolbar->addLink(__('New {0}', __('Attachment')), ['action' => 'add'], ['icon' => 'add']); ?>
<div class="attachments index">
    <table class="ui table striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('model') ?></th>
            <th><?= $this->Paginator->sort('modelid') ?></th>
            <th><?= $this->Paginator->sort('scope') ?></th>
            <th><?= $this->Paginator->sort('type') ?></th>
            <th><?= $this->Paginator->sort('filepath') ?></th>
            <th><?= $this->Paginator->sort('filename') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($attachments as $attachment): ?>
        <tr>
            <td><?= $this->Number->format($attachment->id) ?></td>
            <td><?= h($attachment->model) ?></td>
            <td><?= $this->Number->format($attachment->modelid) ?></td>
            <td><?= h($attachment->scope) ?></td>
            <td><?= h($attachment->type) ?></td>
            <td><?= h($attachment->filepath) ?></td>
            <td><?= h($attachment->filename) ?></td>
            <td class="actions">
                <?php
                $menu = new Backend\Lib\Menu\Menu();
                $menu->add(__('View'), ['action' => 'view', $attachment->id]);

                $dropdown = $menu->add('Dropdown');
                $dropdown->getChildren()->add(
                    __('Edit'),
                    ['action' => 'edit', $attachment->id],
                    ['icon' => 'edit']
                );
                $dropdown->getChildren()->add(
                    __('Delete'),
                    ['action' => 'delete', $attachment->id],
                    ['icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $attachment->id)]
                );
                ?>
                <?= $this->element('Backend.Table/table_row_actions', ['menu' => $menu]); ?>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
    </table>
    <?= $this->element('Backend.Pagination/default'); ?>
</div>
