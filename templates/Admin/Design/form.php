<?php $this->extend('base'); ?>
<?php
$this->assign('title', __d('admin', 'Design Kitchensink'));
$this->loadHelper('Admin.SwitchControl');
$this->loadHelper('Admin.SumoSelect');
$this->loadHelper('Admin.Select2');
;
$this->loadHelper('Admin.DateRangePicker');
?>
<!-- SECTION INPUTS -->
<div class="section-header">
    Form Horizontal
</div>
<div class="row">
    <div class="col-md-12">
        <?= $this->Form->create($form, ['horizontal' => true, 'novalidate' => true]); ?>
        <?= $this->Form->fieldsetStart("Text"); ?>
        <?= $this->Form->control('h_text', ['type' => 'text']); ?>
        <?= $this->Form->control('h_text_disabled', ['type' => 'text', 'disabled' => true, 'value' => 'Disabled']); ?>
        <?= $this->Form->control('h_text_readonly', ['type' => 'text', 'readonly' => true, 'value' => 'Read only']); ?>
        <?= $this->Form->control('h_text_error', ['type' => 'text']); ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->fieldsetStart("Date"); ?>
        <?= $this->Form->control('h_date', ['type' => 'date']); ?>
        <?= $this->Form->control('h_date_picker', ['type' => 'datepicker']); ?>
        <?= $this->Form->control('h_date_range_zero', ['type' => 'daterange']); ?>
        <?= $this->Form->control('h_date_range', ['type' => 'daterange', 'value' => '1999-12-31 - 2000-01-03', 'picker' => [
            'minYear' => 1999,
            'maxYear' => 2001,
            'minDate' => '1999-11-11',
            'maxDate' => '2000-02-02'
        ]]); ?>
        <?= $this->Form->control('h_date_range_custom', ['type' => 'daterange', 'default' => '1999-12-31', 'picker' => [
            'ranges' => true,
        ]]); ?>
        <?= $this->Form->control('h_date_range_single', ['type' => 'daterange', 'default' => '1999-12-31', 'picker' => [
            'singleDatePicker' => true,
        ]]); ?>
        <?= $this->Form->control('h_date_range_time', ['type' => 'daterange', 'default' => '1999-12-31 12:12:00', 'picker' => [
            'timePicker' => true,
        ]]); ?>
        <?= $this->Form->control('h_date_range_time24h', ['type' => 'daterange', 'default' => '2018-12-31 23:23:00 - 2019-01-15 15:15:15', 'picker' => [
            'timePicker' => true,
            'timePicker24Hour' => true,
        ]]); ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->fieldsetStart("Date Time"); ?>
        <?= $this->Form->control('h_datetime', ['type' => 'datetime']); ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->fieldsetStart("Select"); ?>
        <?= $this->Form->control('h_select', ['type' => 'select', 'options' => $selectOptions]); ?>
        <?= $this->Form->control('h_select_empty', ['type' => 'select', 'empty' => 'EMPTY', 'options' => $selectOptions]); ?>
        <?= $this->Form->control('h_select_empty_true', ['type' => 'select', 'empty' => true, 'options' => $selectOptions]); ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->fieldsetStart("Select Select2"); ?>
        <?= $this->Form->control('h_select2_select', ['type' => 'select2', 'options' => $selectOptions]); ?>
        <?= $this->Form->control('h_select2_select_empty', ['type' => 'select2', 'empty' => 'EMPTY', 'options' => $selectOptions]); ?>
        <?= $this->Form->control('h_select2_select_empty_true', ['type' => 'select2', 'empty' => true, 'options' => $selectOptions]); ?>
        <?= $this->Form->control('h_select2_select_multi', ['type' => 'select2', 'options' => $selectOptions, 'multiple' => true]); ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->fieldsetStart("Select Sumoselect"); ?>
        <?= $this->Form->control('h_sumo_select', ['type' => 'sumoselect', 'options' => $selectOptions]); ?>
        <?= $this->Form->control('h_sumo_select_empty', ['type' => 'sumoselect', 'empty' => 'EMPTY', 'options' => $selectOptions]); ?>
        <?= $this->Form->control('h_sumo_select_empty_true', ['type' => 'sumoselect', 'empty' => true, 'options' => $selectOptions]); ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->fieldsetStart("Checkbox"); ?>
        <?= $this->Form->control('h_checkbox', ['type' => 'checkbox']); ?>
        <?= $this->Form->control('h_switch', ['type' => 'switch', 'class' => 'switch']); ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->fieldsetStart("Textarea"); ?>
        <?= $this->Form->control('h_textarea', ['type' => 'textarea']); ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->fieldsetStart("Html Editor"); ?>
        <?= $this->Form->control('h_htmleditor', ['type' => 'htmleditor', 'default' => '<h1>Hello World</h1>']); ?>
        <?= $this->Form->control('h_htmltext', ['type' => 'htmltext', 'default' => '<h1>Hello World</h1>']); ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->fieldsetStart("Code Editor"); ?>
        <?= $this->Form->control('h_codeeditor', ['type' => 'codeeditor']); ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->submit(); ?>
        <?= $this->Form->end(); ?>
        <?php debug($this->request->getData()); ?>
    </div>
</div>




<div class="section-header">
    Form
</div>
<div class="row">
    <div class="col-md-12">
        <?= $this->Form->create(null); ?>
        <?= $this->Form->fieldsetStart("Text"); ?>
        <?= $this->Form->control('text', ['type' => 'text']); ?>
        <?= $this->Form->control('text_disabled', ['type' => 'text', 'disabled' => true, 'value' => 'Disabled']); ?>
        <?= $this->Form->control('text_readonly', ['type' => 'text', 'readonly' => true, 'value' => 'Read only']); ?>
        <?= $this->Form->control('text_error', ['type' => 'text']); ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->fieldsetStart("Select"); ?>
        <?= $this->Form->control('select', ['type' => 'select', 'options' => [1 => 'One', 2 => 'Two']]); ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->fieldsetStart("Checkbox"); ?>
        <?= $this->Form->control('checkbox', ['type' => 'checkbox']); ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->fieldsetStart("Textarea"); ?>
        <?= $this->Form->control('textarea', ['type' => 'textarea']); ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->fieldsetStart("Date"); ?>
        <?= $this->Form->control('date', ['type' => 'date']); ?>
        <?= $this->Form->control('datetime', ['type' => 'datetime']); ?>
        <?= $this->Form->control('datepicker2', ['type' => 'datepicker']); ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->submit(); ?>
        <?= $this->Form->end(); ?>
    </div>
</div>