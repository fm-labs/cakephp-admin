<?php
use Cake\Core\Configure;
?>
<?php $this->loadHelper('Admin.Formatter'); ?>
<?php //$this->loadHelper('Admin.DataTable'); ?>
<?php //$this->loadHelper('Bootstrap.Tabs'); ?>
<div class="entity-view">
    <!--
    <?php if (isset($title)) : ?>
    <h3><?= h($title); ?></h3>
    <?php endif; ?>
    -->
    <!-- Entity -->
    <dl class="dl-horizontal dl-striped">
        <?php foreach ($data as $field) : ?>
            <dt class="<?= $field['class']; ?>">
                <?= h($field['label']); ?>
            </dt>
            <dd>
                <?= $this->Formatter->format($field['value'], $field['formatter'], $field['formatterArgs'], $entity); ?>&nbsp;
            </dd>
        <?php endforeach; ?>
    </dl>

    <?php debug($data); ?>
</div>