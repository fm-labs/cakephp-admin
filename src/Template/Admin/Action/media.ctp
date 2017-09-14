<?php $this->loadHelper('Form'); ?>
<?= $this->Form->create($entity); ?>
<?php
echo $this->Form->fieldsetStart(__('Media'));
foreach ((array)$this->get('fields') as $fieldName => $field) {
    echo $this->Form->input($fieldName, ['type' => 'media_picker', 'config' => $field['config'], 'multiple' => $field['multiple']]);
}
echo $this->Form->button(__('Update'));
echo $this->Form->fieldsetEnd();
?>
<?= $this->Form->end(); ?>