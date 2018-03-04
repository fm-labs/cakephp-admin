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
//$title = $this->get('title', @array_pop(explode('\\', get_class($entity))));
$viewOptions = (array) $this->get('viewOptions');

$formOptions = $this->get('form.options', []);
$inputFields = $this->get('fields', []);
$inputOptions = $this->get('inputs.options', []);
$fieldsets = $this->get('fieldsets');
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
<div class="form form-edit">

    <?php if ($this->fetch('content')) {
        echo $this->fetch('content');
    } else {
        //echo $this->cell('Backend.EntityView', [ $entity ], $viewOptions)->render();

        echo $this->Form->create($entity, $formOptions);
        if ($fieldsets) {
            foreach($fieldsets as $fieldset) {
                echo $this->Form->fieldsetStart($fieldset);
                foreach($fieldset['fields'] as $field) {
                    $fieldConfig = (isset($fields[$field]) && isset($fields[$field]['input'])) ? $fields[$field]['input'] : [];
                    echo $this->Form->input($field, $fieldConfig);
                }
                echo $this->Form->fieldsetEnd();
            }

        } else {
            echo $this->Form->allInputs($inputFields, $inputOptions);
        }
        echo $this->Form->button(__d('backend','Submit'));
        echo $this->Form->end();
    }

    debug($inputFields);
    ?>

    <?php if (\Cake\Core\Configure::read('debug')): ?>
        <small><?php echo h(__FILE__); ?></small>
    <?php endif; ?>
</div>
