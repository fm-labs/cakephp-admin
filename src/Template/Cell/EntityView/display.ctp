<?php
use Cake\Core\Configure;
use Cake\Utility\Inflector;
?>
<?php $this->loadHelper('Backend.Formatter'); ?>
<?php $this->loadHelper('Backend.DataTable'); ?>
<?php $this->loadHelper('Bootstrap.Tabs'); ?>
<div class="entity-view">

    <div class="box">
        <?php if (isset($title)): ?>
        <div class="box-header with-border">
            <h3 class="box-title"><?= h($title); ?></h3>
        </div>
        <?php endif; ?>
        <div class="box-body">
            <!-- Entity -->
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
        </div>
    </div>

</div>