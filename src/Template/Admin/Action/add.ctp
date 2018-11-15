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
$fields = $this->get('fields');
$whitelist = $this->get('fields.whitelist');
$formOptions = $this->get('form.options', ['horizontal' => true]);

/**
 * Helpers
 */
$this->loadHelper('Backend.Chosen');
$this->loadHelper('Bootstrap.Tabs');

/**
 * Extend
 */
$this->extend('Backend./Admin/Base/form_tabs');
?>
<div class="form form-add">

    <?php if ($this->fetch('content')) {
        echo $this->fetch('content');
    } else {
        echo $this->Form->create($entity, $formOptions);
        if ($whitelist) {
            foreach($whitelist as $field) {
                $fieldConfig = (isset($fields[$field]) && isset($fields[$field]['input'])) ? $fields[$field]['input'] : [];
                echo $this->Form->input($field, $fieldConfig);
            }
        } else {
            echo $this->Form->allInputs();
        }
        echo $this->Form->button(__d('backend','Submit'));
        echo $this->Form->end();
    }
    ?>
</div>
