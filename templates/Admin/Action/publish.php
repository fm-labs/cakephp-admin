<?php $this->loadHelper('Form'); ?>
<?= $this->Form->create($entity); ?>
<?php
echo $this->Form->fieldsetStart(__d('backend','Publishing'));
echo $this->Form->control('is_published', ['type'=> 'checkbox', 'hidden' => true]);
echo $this->Form->control('publish_start_date', ['type' => 'datepicker']);
echo $this->Form->control('publish_end_date', ['type' => 'datepicker']);
echo $this->Form->button(__d('backend','Update'));
echo $this->Form->fieldsetEnd();
?>
<?= $this->Form->end(); ?>