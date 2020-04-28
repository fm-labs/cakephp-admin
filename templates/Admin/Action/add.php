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
$viewOptions = (array)$this->get('viewOptions');
$fieldsets = $this->get('fieldsets');
$fields = $this->get('fields');
$formOptions = $this->get('form.options', ['horizontal' => true]);

/**
 * Helpers
 */
//$this->loadHelper('Admin.Chosen');
$this->loadHelper('Bootstrap.Tabs');

/**
 * Extend
 */
//$this->extend('Admin./Base/form');
?>
<div class="form form-add">

    <?php
    if ($this->fetch('content')) {
        echo $this->fetch('content');
    } else {
        echo $this->Form->create($entity, $formOptions);
        if ($fieldsets) {
            foreach ($fieldsets as $fieldset) {
                $this->Form->allControls($fieldset['inputs'], $fieldset['options']);
            }
        } elseif ($fields) {
            foreach ($fields as $field => $config) {
                echo $this->Form->control($field, $config);
            }
        } else {
            $this->Form->allControls();
        }
        echo $this->Form->button(__d('admin', 'Submit'));
        echo $this->Form->end();
    }
    ?>
</div>
