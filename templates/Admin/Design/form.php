<?php
/**
 * @var array $selectOptions
 * @var \Cake\Form\Form $form
 */
?>
<?php
$checkboxOptions = $radioOptions = ['On', 'Suspend', 'Off'];
$helpText = "This is some help text to better describe the purpose of this field or the behavior when used. By default there is no help text.";

$this->extend('base');
$this->assign('title', __d('admin', 'Design Kitchensink'));
//$this->loadHelper('Sugar.SwitchControl');
//$this->loadHelper('Sugar.SumoSelect');
//$this->loadHelper('Sugar.Select2');
//$this->loadHelper('Sugar.DateRangePicker');
?>
<div class="row">
    <div class="col-md-6">
        <div class="section-header">
            Form Horizontal
        </div>
        <div class="form">
            <?= $this->Form->create($form, ['horizontal' => true, 'novalidate' => true]); ?>

            <!-- H | Text -->
            <?= $this->Form->fieldsetStart("Text"); ?>
            <?= $this->Form->control('h_text', ['type' => 'text']); ?>
            <?= $this->Form->control('h_text_disabled', ['type' => 'text', 'disabled' => true, 'value' => 'Disabled']); ?>
            <?= $this->Form->control('h_text_readonly', ['type' => 'text', 'readonly' => true, 'value' => 'Read only']); ?>
            <?= $this->Form->control('h_text_error', ['type' => 'text']); ?>
            <?= $this->Form->control('h_text_help', ['type' => 'text', 'help' => $helpText]); ?>
            <?= $this->Form->fieldsetEnd(); ?>

            <!-- H | Date -->
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

            <!-- H | DateTime -->
            <?= $this->Form->fieldsetStart("Date Time"); ?>
            <?= $this->Form->control('h_datetime', ['type' => 'datetime']); ?>
            <?= $this->Form->fieldsetEnd(); ?>

            <!-- H | Standard selectbox -->
            <?= $this->Form->fieldsetStart("Select"); ?>
            <?= $this->Form->control('h_select', ['type' => 'select', 'options' => $selectOptions]); ?>
            <?= $this->Form->control('h_select_empty', ['type' => 'select', 'empty' => 'EMPTY', 'options' => $selectOptions]); ?>
            <?= $this->Form->control('h_select_empty_true', ['type' => 'select', 'empty' => true, 'options' => $selectOptions]); ?>
            <?= $this->Form->control('h_select_multi', ['type' => 'select', 'options' => $selectOptions, 'multiple' => true, 'help' => $helpText]); ?>
            <?= $this->Form->fieldsetEnd(); ?>

            <!-- H | Select2 selectbox -->
            <?= $this->Form->fieldsetStart("Select Select2"); ?>
            <?php if ($this->helpers()->has('Sumoselect')): ?>
                <?= $this->Form->control('h_select2_select', ['type' => 'select2', 'options' => $selectOptions]); ?>
                <?= $this->Form->control('h_select2_select_empty', ['type' => 'select2', 'empty' => 'EMPTY', 'options' => $selectOptions]); ?>
                <?= $this->Form->control('h_select2_select_empty_true', ['type' => 'select2', 'empty' => true, 'options' => $selectOptions, 'help' => $helpText]); ?>
                <?= $this->Form->control('h_select2_select_multi', ['type' => 'select2', 'options' => $selectOptions, 'multiple' => true, 'help' => $helpText]); ?>
            <?php else: ?>
                <div class="well">Select2 not loaded</div>
            <?php endif; ?>
            <?= $this->Form->fieldsetEnd(); ?>

            <!-- H | Sumo selectbox -->
            <?= $this->Form->fieldsetStart("Select Sumoselect"); ?>
            <?php if ($this->helpers()->has('Sumoselect')): ?>
                <?= $this->Form->control('h_sumo_select', ['type' => 'sumoselect', 'options' => $selectOptions, 'help' => $helpText]); ?>
                <?= $this->Form->control('h_sumo_select_multi', ['type' => 'sumoselect', 'options' => $selectOptions, 'multiple' => true]); ?>
                <?= $this->Form->control('h_sumo_select_empty', ['type' => 'sumoselect', 'empty' => 'EMPTY', 'options' => $selectOptions]); ?>
                <?= $this->Form->control('h_sumo_select_empty_true', ['type' => 'sumoselect', 'empty' => true, 'options' => $selectOptions]); ?>
            <?php else: ?>
                <div class="well">Sumoselect not loaded</div>
            <?php endif; ?>
            <?= $this->Form->fieldsetEnd(); ?>

            <!-- H | Checkbox -->
            <?= $this->Form->fieldsetStart("Checkbox"); ?>
            <?= $this->Form->control('h_checkbox', ['type' => 'checkbox', 'help' => $helpText]); ?>
            <?= $this->Form->control('h_checkbox_disabled', ['type' => 'checkbox', 'disabled' => true]); ?>
            <?= $this->Form->control('h_checkbox_readonly', ['type' => 'checkbox', 'readonly' => true]); ?>
            <?= $this->Form->control('h_checkbox_multi', ['type' => 'select', 'options' => $checkboxOptions, 'multiple' => 'checkbox']); ?>
            <?= ''//$this->Form->control('h_switch', ['type' => 'switch', 'class' => 'switch']); ?>
            <?= $this->Form->fieldsetEnd(); ?>

            <!-- H | Radio -->
            <?= $this->Form->fieldsetStart("Radio"); ?>
            <?= $this->Form->control('h_radio', ['type' => 'radio', 'options' => $radioOptions, 'help' => $helpText]); ?>
            <?= $this->Form->control('h_radio_disabled', ['type' => 'radio', 'disabled' => true, 'options' => $radioOptions]); ?>
            <?= $this->Form->control('h_radio_readonly', ['type' => 'radio', 'readonly' => true, 'options' => $radioOptions]); ?>
            <?= ''//$this->Form->control('h_switch', ['type' => 'switch', 'class' => 'switch']); ?>
            <?= $this->Form->fieldsetEnd(); ?>

            <!-- H | Textarea -->
            <?= $this->Form->fieldsetStart("Textarea"); ?>
            <?= $this->Form->control('h_textarea', ['type' => 'textarea', 'help' => $helpText]); ?>
            <?= $this->Form->fieldsetEnd(); ?>

            <!-- H | Htmleditor -->
            <?= $this->Form->fieldsetStart("Html Editor"); ?>
            <?= $this->Form->control('h_htmleditor', ['type' => 'htmleditor', 'default' => '<h1>Hello World</h1>', 'help' => $helpText]); ?>
            <?= $this->Form->control('h_htmltext', ['type' => 'htmltext', 'default' => '<h1>Hello World</h1>']); ?>
            <?= $this->Form->fieldsetEnd(); ?>

            <!-- H | Codeeditor -->
            <?= $this->Form->fieldsetStart("Code Editor"); ?>
            <?= $this->Form->control('h_codeeditor', ['type' => 'codeeditor']); ?>
            <?= $this->Form->fieldsetEnd(); ?>
            <?= $this->Form->submit(); ?>
            <?= $this->Form->end(); ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="section-header">
            Form
        </div>
        <div class="form">
            <?= $this->Form->create($form); ?>
            <?= $this->Form->fieldsetStart("Text"); ?>
            <?= $this->Form->control('text', ['type' => 'text']); ?>
            <?= $this->Form->control('text_disabled', ['type' => 'text', 'disabled' => true, 'value' => 'Disabled']); ?>
            <?= $this->Form->control('text_readonly', ['type' => 'text', 'readonly' => true, 'value' => 'Read only']); ?>
            <?= $this->Form->control('text_error', ['type' => 'text', 'help' => $helpText]); ?>
            <?= $this->Form->fieldsetEnd(); ?>

            <?= $this->Form->fieldsetStart("Select"); ?>
            <?= $this->Form->control('select', ['type' => 'select', 'options' => [1 => 'One', 2 => 'Two'], 'help' => $helpText]); ?>
            <?= $this->Form->fieldsetEnd(); ?>

            <!-- Checkbox -->
            <?= $this->Form->fieldsetStart("Checkbox"); ?>
            <?= $this->Form->control('checkbox', ['type' => 'checkbox']); ?>
            <?= $this->Form->control('checkbox_disabled', ['type' => 'checkbox', 'disabled' => true, 'help' => $helpText]); ?>
            <?= $this->Form->control('checkbox_readonly', ['type' => 'checkbox', 'readonly' => true]); ?>
            <?= $this->Form->control('checkbox_multi', ['type' => 'select', 'multiple' => 'checkbox', 'options' => $checkboxOptions]); ?>
            <?= $this->Form->fieldsetEnd(); ?>

            <!-- Radio -->
            <?= $this->Form->fieldsetStart("Radio"); ?>
            <?= $this->Form->control('radio', ['type' => 'radio', 'options' => $radioOptions, 'help' => $helpText]); ?>
            <?= $this->Form->control('radio_disabled', ['type' => 'radio', 'disabled' => true, 'options' => $radioOptions]); ?>
            <?= $this->Form->control('radio_readonly', ['type' => 'radio', 'readonly' => true, 'options' => $radioOptions]); ?>
            <?= $this->Form->fieldsetEnd(); ?>

            <?= $this->Form->fieldsetStart("Textarea"); ?>
            <?= $this->Form->control('textarea', ['type' => 'textarea', 'help' => $helpText]); ?>
            <?= $this->Form->fieldsetEnd(); ?>

            <?= $this->Form->fieldsetStart("Date"); ?>
            <?= $this->Form->control('date', ['type' => 'date']); ?>
            <?= $this->Form->control('datetime', ['type' => 'datetime']); ?>
            <?= $this->Form->control('datepicker2', ['type' => 'datepicker', 'help' => $helpText]); ?>
            <?= $this->Form->fieldsetEnd(); ?>

            <?= $this->Form->submit(); ?>
            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>
