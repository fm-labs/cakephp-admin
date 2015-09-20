<?php $this->Html->addCrumb(__('Attachments'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('New {0}', __('Attachment'))); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Attachments')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Add {0}', __('Attachment')) ?>
    </h2>
    <?= $this->Form->create($attachment); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('model');
                echo $this->Form->input('modelid');
                echo $this->Form->input('scope');
                echo $this->Form->input('type');
                echo $this->Form->input('filepath', ['type' => 'imageselect', 'options' => $galleryList]);
                echo $this->Form->input('filename');
                echo $this->Form->input('title');
                echo $this->Form->input('desc_text');
                echo $this->Form->input('mimetype');
                echo $this->Form->input('filesize');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__('Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>