<div>
    <h1 class="ui header">Backend KitchenSink</h1>

    <div class="ui form">
        <?= $this->Form->create(null); ?>
        <?= $this->Form->input('string'); ?>
        <?= $this->Form->input('text', ['type' => 'textarea']); ?>

        <div class="three fields">
            <?= $this->Form->input('string1'); ?>
            <?= $this->Form->input('string2'); ?>
            <?= $this->Form->input('string3'); ?>
        </div>

        <div class="ui divider"></div>
        <?= $this->Form->input('htmltext', ['type' => 'htmltext', 'class' => 'html']); ?>
        <?= $this->Form->input('htmleditor', ['type' => 'htmleditor', 'class' => 'html']); ?>

        <div class="ui divider"></div>
        <?= $this->Form->input('select', ['type' => 'select', 'options' => ['Apple', 'Bananas', 'Grapefruit']]); ?>
        <?= $this->Form->input('select_multiple', ['type' => 'select', 'multiple' => true, 'options' => ['Apple', 'Bananas', 'Grapefruit']]); ?>

        <div class="ui divider"></div>
        <?= $this->Form->input('date', ['type' => 'datepicker']); ?>
        <?= $this->Form->input('time', ['type' => 'timepicker']); ?>
        <?= '' //$this->Form->input('datetime', ['type' => 'datetimepicker']); ?>

        <div class="ui divider"></div>
        <?= $this->Form->button('Submit', ['class' => 'orange']); ?>
        <?= $this->Form->button('Reset', ['type' => 'reset']); ?>


        <div class="ui divider"></div>
        <?= $this->Form->submit('Submit'); ?>
        <?= $this->Form->end(); ?>
    </div>
</div>