<?php $this->Html->addCrumb(__('Attachments'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($attachment->title); ?>
<?= $this->Toolbar->addLink(
    __('Edit {0}', __('Attachment')),
    ['action' => 'edit', $attachment->id],
    ['icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __('Delete {0}', __('Attachment')),
    ['action' => 'delete', $attachment->id],
    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $attachment->id)]) ?>

<?= $this->Toolbar->addLink(
    __('List {0}', __('Attachments')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Attachment')),
    ['action' => 'add'],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__('More')); ?>
<?= $this->Toolbar->endGroup(); ?>
<div class="attachments view">
    <h2 class="ui header">
        <?= h($attachment->title) ?>
    </h2>
    <table class="ui attached celled striped table">
        <!--
        <thead>
        <tr>
            <th><?= __('Label'); ?></th>
            <th><?= __('Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __('Model') ?></td>
            <td><?= h($attachment->model) ?></td>
        </tr>
        <tr>
            <td><?= __('Scope') ?></td>
            <td><?= h($attachment->scope) ?></td>
        </tr>
        <tr>
            <td><?= __('Type') ?></td>
            <td><?= h($attachment->type) ?></td>
        </tr>
        <tr>
            <td><?= __('Filepath') ?></td>
            <td><?= h($attachment->filepath) ?></td>
        </tr>
        <tr>
            <td><?= __('Filename') ?></td>
            <td><?= h($attachment->filename) ?></td>
        </tr>
        <tr>
            <td><?= __('Title') ?></td>
            <td><?= h($attachment->title) ?></td>
        </tr>
        <tr>
            <td><?= __('Mimetype') ?></td>
            <td><?= h($attachment->mimetype) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($attachment->id) ?></td>
        </tr>
        <tr>
            <td><?= __('Modelid') ?></td>
            <td><?= $this->Number->format($attachment->modelid) ?></td>
        </tr>
        <tr>
            <td><?= __('Filesize') ?></td>
            <td><?= $this->Number->format($attachment->filesize) ?></td>
        </tr>


        <tr class="date">
            <td><?= __('Created') ?></td>
            <td><?= h($attachment->created) ?></td>
        </tr>
        <tr class="date">
            <td><?= __('Modified') ?></td>
            <td><?= h($attachment->modified) ?></td>
        </tr>

        <tr class="text">
            <td><?= __('Desc') ?></td>
            <td><?= $this->Text->autoParagraph(h($attachment->desc)); ?></td>
        </tr>
    </table>
</div>
