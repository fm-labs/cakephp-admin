<?php $this->loadHelper('Bootstrap.Ui'); ?>
<div class="form-info">
    <h4>Publishing</h4>
    <strong>Status (<?= intval($entity->is_published); ?>)</strong>
    <p>
        <?= $this->Ui->statusLabel($entity->is_published, [], [
            0 => [__d('admin','Unpublished'), 'danger'],
            1 => [__d('admin','Published'),'success']
        ]); ?>
        <?php if (!$entity->is_published) {
            echo $this->Html->link(__d('admin','Publish'), ['action' => 'publish', $entity->id, 'state' => 1]);
        } else {
            echo $this->Html->link(__d('admin','Unpublish'), ['action' => 'publish', $entity->id, 'state' => 0]);
        }?>
    </p>
</div>