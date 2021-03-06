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
;
$this->loadHelper('Bootstrap.Tabs');

$fields = $this->get('fields');
$whitelist = $this->get('fields.whitelist');
?>
<div class="edit">
    <?php $this->Tabs->create(); ?>
    <?= $this->Tabs->add($this->fetch('title', $title)); ?>

    <div class="row">
        <div class="col-md-9">
            <?php if ($this->fetch('content')) {
                echo $this->fetch('content');
            } else {
                echo $this->Form->create($entity);
                if ($whitelist) {
                    foreach($whitelist as $field) {
                        $fieldConfig = (isset($fields[$field]) && isset($fields[$field]['input'])) ? $fields[$field]['input'] : [];
                        echo $this->Form->control($field, $fieldConfig);
                    }
                } else {
                    echo $this->Form->allControls();
                }
                echo $this->Form->button(__d('admin','Submit'));
                echo $this->Form->end();
            }
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
