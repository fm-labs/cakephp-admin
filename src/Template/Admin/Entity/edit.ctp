<?php

$this->Form->addContextProvider('entity_form', function($request, $context) {
    if ($context['entity'] instanceof \Banana\Form\EntityForm) {
        return new \Banana\View\Form\EntityFormContext($request, $context);
    }
});
?>
<div class="index">

    <?php echo $this->Form->create($form, ['horizontal' => true]); ?>
    <?php echo $this->Form->allInputs($form->controls(), ['fieldset' => false] ); ?>
    <?php echo $this->Form->button(__('Save')); ?>
    <?php echo $this->Form->end(); ?>

    <?php debug($form->controls()); ?>
    <?php //debug($settings); ?>
</div>
