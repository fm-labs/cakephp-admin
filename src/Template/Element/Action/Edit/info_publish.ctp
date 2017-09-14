<?php $this->loadHelper('Bootstrap.Ui'); ?>
<div class="form-info">
    <h4>Publishing</h4>
    <strong>Status (<?= intval($entity->is_published); ?>)</strong>
    <p>
        <?= $this->Ui->statusLabel($entity->is_published, [], [
            0 => [__('Unpublished'), 'danger'],
            1 => [__('Published'),'success']
        ]); ?>
        <?php if (!$entity->is_published) {
            echo $this->Html->link(__('Publish'), ['action' => 'publish', $entity->id, 'state' => 1]);
        } else {
            echo $this->Html->link(__('Unpublish'), ['action' => 'publish', $entity->id, 'state' => 0]);
        }?>
    </p>
</div>