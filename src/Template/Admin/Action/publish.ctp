<?php $this->loadHelper('Form'); ?>
<?= $this->Form->create($entity); ?>
<?php
echo $this->Form->fieldsetStart(__('Publishing'));
echo $this->Form->input('is_published');
echo $this->Form->input('publish_start_date', ['type' => 'datepicker']);
echo $this->Form->input('publish_end_date', ['type' => 'datepicker']);
echo $this->Form->button(__('Update'));
echo $this->Form->fieldsetEnd();
?>
<?= $this->Form->end(); ?>