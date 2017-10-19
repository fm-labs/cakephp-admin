<?php
use Cake\Core\Configure;
use Cake\Utility\Inflector;
?>
<?php $this->loadHelper('Backend.Formatter'); ?>
<div class="entity-view">

    <?php if ($title): ?>
    <h1><?= h($title); ?></h1>
    <?php endif; ?>

    <dl class="dl-horizontal dl-striped">
        <?php foreach ($data as $field): ?>

            <dt class="<?= $field['class']; ?>">
                <?= h($field['label']); ?>
            </dt>
            <dd>
                <?= $this->Formatter->format( $field['value'], $field['formatter'], $field['formatterArgs'], $entity ); ?>
            </dd>
        <?php endforeach; ?>
    </dl>

    <?php if ($debug === true && 1 == 2): ?>
    <div class="debug">
        <?php debug($associations); ?>
        <?php debug($schema); ?>
        <?php debug($data); ?>
        <?php debug($entity->toArray()); ?>
    </div>
    <?php endif; ?>
</div>