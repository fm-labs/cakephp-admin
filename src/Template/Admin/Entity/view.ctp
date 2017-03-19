<?php if ($this->get('exception')): ?>
<div class="alert alert-danger">
    <?= h($this->get('exception')->getMessage()); ?>
</div>
<?php endif; ?>
<?php if (!$this->get('entity')): ?>
    <div class="alert alert-danger">
        <?= __('Entity not found'); ?>
    </div>
    <?php return false; ?>
<?php endif; ?>
<?php
$this->loadHelper('Bootstrap.Tabs');
?>
<div class="backend-entity-view backend-view view">
    <h1><?= __('Entity {0} #{1}', $modelName, $modelId); ?></h1>
    <?php $this->Tabs->create(); ?>
    <?php $this->Tabs->add(__('Entity Form')); ?>
    <!--
    <?= $this->Form->create($entity); ?>
    <?= $this->Form->input('id', ['type' => 'text']); ?>
    <?= $this->Form->input('name', ['type' => 'text']); ?>
    <?= $this->Form->input('test', ['type' => 'text']); ?>
    <?= $this->Form->input('test_attribute', ['type' => 'text']); ?>
    <?= $this->Form->end(); ?>
    -->

    <?= $this->cell('Eav.AttributesFormInputs', [$entity, $modelName]); ?>

    <?php $this->Tabs->add(__('Attributes')); ?>
        <?php debug($attributes); ?>
        <?php debug($attributesAvailable); ?>
        <?php debug($entity->entity_attribute_values); ?>
    <?php $this->Tabs->add(__('Entity view')); ?>
        <?= $this->cell('Backend.EntityView', [ $entity ], [
            'title' => false,
            'model' => $this->get('modelName'),
        ]); ?>

    <?php echo $this->Tabs->render(); ?>
</div>
