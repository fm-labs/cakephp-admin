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

$formOptions = $this->get('form.options', ['horizontal' => true]);
$inputFields = $this->get('fields', []);
$inputOptions = $this->get('inputs.options', []);
$fieldsets = $this->get('fieldsets');
$translations = $this->get('translations.languages');
//$whitelist = $this->get('fields.whitelist');
//$blacklist = $this->get('fields.blacklist');
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

    <?php if ($translations): ?>
    <div>
        <p>
            <strong>Translations: </strong>
            <?php foreach($translations as $lang => $langLabel): ?>
                <?php
                $options = [
                    'data-lang' => $lang,
                    'data-title' => __d('backend', 'Edit {0} translation', $langLabel),
                    'class' => ($lang == Cake\I18n\I18n::locale()) ? 'active' : ''
                ];
                $langLabel = ($lang == $translation) ? '[' . $langLabel . ']' : $langLabel;
                ?>
                <?= $this->Html->link($langLabel, ['action' => 'edit', $entity->id, 'translation' => $lang], $options); ?>&nbsp;
            <?php endforeach; ?>
        </p>
    </div>
    <?php endif; ?>

    <?php if ($this->fetch('content')) {
        echo $this->fetch('content');
    } else {
        //echo $this->cell('Backend.EntityView', [ $entity ], $viewOptions)->render();
        echo $this->Form->create($entity, $formOptions);
        if ($fieldsets) {
            foreach ($fieldsets as $fieldset) {
                echo $this->Form->fieldsetStart($fieldset);
                foreach ($fieldset['fields'] as $field) {
                    //if (!empty($whitelist) && !in_array($field, $whitelist)) continue;
                    //if (!empty($blacklist) && in_array($field, $blacklist)) continue;
                    $fieldConfig = (isset($fields[$field]) && isset($fields[$field]['input'])) ? $fields[$field]['input'] : [];
                    echo $this->Form->input($field, $fieldConfig);
                }
                echo $this->Form->fieldsetEnd();
            }
        } else {
            echo $this->Form->allInputs($inputFields, $inputOptions);
        }
        echo $this->Form->submit(__d('backend', 'Save changes'));
        echo $this->Form->end();
    }
    ?>
</div>
