<?php $this->loadHelper('Form'); ?>
<div class="edit">
    <?= $this->Form->create($this->get('entity'), ['class' => 'form']); ?>
    <?php
    echo $this->Form->fieldsetStart(__d('admin','Publishing'));
    echo $this->Form->control('is_published', ['type'=> 'checkbox']);
    echo $this->Form->control('publish_start_date', ['type' => 'datepicker']);
    echo $this->Form->control('publish_end_date', ['type' => 'datepicker']);
    echo $this->Form->button(__d('admin','Update'));
    echo $this->Form->fieldsetEnd();
    ?>
    <?= $this->Form->end(); ?>
</div>
