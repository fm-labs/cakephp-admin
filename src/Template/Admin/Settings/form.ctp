<?php $this->Breadcrumbs->add(__d('backend','Settings')); ?>
<?php $this->Toolbar->addLink(
    __d('backend','Dump {0}', __d('backend','Settings')),
    ['action' => 'dump'],
    ['data-icon' => 'download']
) ?>
<?php

$this->Form->addContextProvider('settings_form', function($request, $context) {
    if ($context['entity'] instanceof \Settings\Form\SettingsForm) {
        return new \Settings\View\Form\SettingsFormContext($request, $context);
    }
});
?>
<div class="settings index">

    <?php echo $this->Form->create($form, ['horizontal' => true]); ?>
    <?php echo $this->Form->allInputs($form->inputs(), ['fieldset' => false] ); ?>
    <?php echo $this->Form->button(__d('backend','Save')); ?>
    <?php echo $this->Form->end(); ?>

    <?php debug($form->inputs()); ?>
    <?php //debug($settings); ?>
</div>
