<?php $this->loadHelper('Form'); ?>
<?= $this->Form->create($entity); ?>
<?php
echo $this->Form->fieldsetStart(__d('backend','Publishing'));
echo $this->Form->input('is_published');
echo $this->Form->input('publish_start_date', ['type' => 'datepicker']);
echo $this->Form->input('publish_end_date', ['type' => 'datepicker']);
echo $this->Form->button(__d('backend','Update'));
echo $this->Form->fieldsetEnd();
?>
<?= $this->Form->end(); ?>