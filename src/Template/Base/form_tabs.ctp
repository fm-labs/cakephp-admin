<?php
/**
 * View Action
 *
 * Renders entity with EntityView cell
 *
 * View variables:
 * - entity: Entity instance
 * - viewOptions: EntityView options array
 */
$entity = $this->get('entity');
$title = $this->get('title', @array_pop(explode('\\', get_class($entity))));
$viewOptions = (array) $this->get('viewOptions');

/**
 * Helpers
 */
$this->loadHelper('Backend.Chosen');
$this->loadHelper('Bootstrap.Tabs');

$fields = $this->get('fields');
$whitelist = $this->get('fields.whitelist');
?>
<div class="form-tabs">
    <?php $this->Tabs->create(); ?>
    <?= $this->Tabs->add($this->fetch('title', $title)); ?>

    <div class="row">
        <div class="col-md-9">
            <?php
            echo $this->fetch('content');
            ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->fetch('form_elements'); ?>
        </div>
    </div>

    <?php if ($this->get('tabs')): ?>
        <?php foreach ((array) $this->get('tabs') as $tabId => $tab): ?>
            <?php $this->Tabs->add($tab['title'], $tab); ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php echo $this->Tabs->render(); ?>

    <?php if (\Cake\Core\Configure::read('debug')): ?>
        <small><?php echo h(__FILE__); ?></small>
    <?php endif; ?>
</div>
