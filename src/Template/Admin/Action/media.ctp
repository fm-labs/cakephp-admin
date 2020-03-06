<?php $this->loadHelper('Form'); ?>
<?= $this->Form->create($entity); ?>
<?php
echo $this->Form->fieldsetStart(__d('backend','Media'));
foreach ((array)$this->get('fields') as $fieldName => $field) {
    echo $this->Form->control($fieldName, ['type' => 'media_picker', 'config' => $field['config'], 'multiple' => $field['multiple']]);
}
echo $this->Form->button(__d('backend','Update'));
echo $this->Form->fieldsetEnd();
?>
<?= $this->Form->end(); ?>