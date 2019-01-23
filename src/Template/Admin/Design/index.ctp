<?php
$this->assign('title', __d('backend', 'Design Kitchensink'));
?>
<style>
    .design-index .section-header {
        padding: 0.5em;
        margin: 2em 0 1em 0;
        color: #ff9900;
        border-radius: 10px 0 10px 0;
        border: 1px solid #FFF;
    }
</style>
<div class="design-index index">

    <!-- SECTION JS Alerts -->
    <div class="section-header">
        Javascript Alerts & Confirmation Boxes
    </div>

    <a href="#" id="test-js-alert">Test Alert on click event</a> |
    <a href="#" id="test-js-confirm">Test Confirm on click event</a> |
    <a onclick="alert('Buyaaa!')">Test Alert</a> |
    <a onclick="confirm('You sure?')">Test Confirmation</a> |
    <a onclick="prompt('You sure?')">Test Prompt</a>
    <br />
    <?= $this->Html->link('Alert', 'javascript:alert("Hello")'); ?> |
    <?= $this->Html->link('Confirm', '#', ['confirm' => 'You sure?']); ?>
    <?php $this->append('script'); ?>
    <script>
        $('#test-js-alert').click(function(ev) {

            alert("Test");

            ev.stopPropagation();
            return false;
        })
        $('#test-js-confirm').click(function(ev) {

            var confirmed = confirm("Confirm?");
            console.log("CONFIRM RESULT", confirmed);

            ev.stopPropagation();
            return false;
        })
    </script>
    <?php $this->end(); ?>

    <!-- SECTION INPUTS -->
    <div class="section-header">
        Form
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $this->Form->create(null); ?>
            <?= $this->Form->input('text', ['type' => 'text']); ?>
            <?= $this->Form->input('text_disabled', ['type' => 'text', 'disabled' => true, 'value' => 'Disabled']); ?>
            <?= $this->Form->input('text_readonly', ['type' => 'text', 'readonly' => true, 'value' => 'Read only']); ?>
            <?= $this->Form->input('text_error', ['type' => 'text']); ?>
            <?= $this->Form->input('select', ['type' => 'select', 'options' => [1 => 'One', 2 => 'Two']]); ?>
            <?= $this->Form->input('checkbox', ['type' => 'checkbox']); ?>
            <?= $this->Form->input('textarea', ['type' => 'textarea']); ?>
            <?= $this->Form->input('date', ['type' => 'date']); ?>
            <?= $this->Form->input('datetime', ['type' => 'datetime']); ?>
            <?= $this->Form->input('datepicker', ['type' => 'datepicker']); ?>
            <?= $this->Form->submit(); ?>
            <?= $this->Form->end(); ?>
        </div>
    </div>
    <div class="section-header">
        Form Horizontal
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $this->Form->create(null, ['horizontal' => true]); ?>
            <?= $this->Form->input('text', ['type' => 'text']); ?>
            <?= $this->Form->input('text_disabled', ['type' => 'text', 'disabled' => true, 'value' => 'Disabled']); ?>
            <?= $this->Form->input('text_readonly', ['type' => 'text', 'readonly' => true, 'value' => 'Read only']); ?>
            <?= $this->Form->input('text_error', ['type' => 'text']); ?>
            <?= $this->Form->input('select2', ['type' => 'select', 'options' => [1 => 'One', 2 => 'Two']]); ?>
            <?= $this->Form->input('checkbox', ['type' => 'checkbox']); ?>
            <?= $this->Form->input('textarea', ['type' => 'textarea']); ?>
            <?= $this->Form->input('date', ['type' => 'date']); ?>
            <?= $this->Form->input('datetime', ['type' => 'datetime']); ?>
            <?= $this->Form->input('datepicker2', ['type' => 'datepicker']); ?>
            <?= $this->Form->submit(); ?>
            <?= $this->Form->end(); ?>
        </div>
    </div>

    <!-- SECTION TABLES -->
    <div class="section-header">
        Tables
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-heading">Table</div>
                <div class="box-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Col</th>
                            <th>Col</th>
                            <th>Col</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Foo Bar</td>
                            <td>Foo Bar</td>
                            <td>Foo Bar</td>
                        </tr>
                        <tr>
                            <td>Foo Bar</td>
                            <td>Foo Bar</td>
                            <td>Foo Bar</td>
                        </tr>
                        <tr>
                            <td>Foo Bar</td>
                            <td>Foo Bar</td>
                            <td>Foo Bar</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-heading">Table</div>
                <div class="box-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Col</th>
                            <th>Col</th>
                            <th>Col</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Foo Bar</td>
                            <td>Foo Bar</td>
                            <td>Foo Bar</td>
                        </tr>
                        <tr>
                            <td>Foo Bar</td>
                            <td>Foo Bar</td>
                            <td>Foo Bar</td>
                        </tr>
                        <tr>
                            <td>Foo Bar</td>
                            <td>Foo Bar</td>
                            <td>Foo Bar</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- SECTION BOXES -->
    <div class="section-header">
        Boxes
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-heading">Title</div>
                <div class="box-body">Hi!</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-heading">Title</div>
                <div class="box-body">Hi!</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-heading">Title</div>
                <div class="box-body">Hi!</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-heading">Title</div>
                <div class="box-body">Hi!</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-heading">Title</div>
                <div class="box-body">Hi!</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="box box-default">
                <div class="box-heading">Title</div>
                <div class="box-body">Hi!</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-default">
                <div class="box-heading">Title</div>
                <div class="box-body">Hi!</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-default">
                <div class="box-heading">Title</div>
                <div class="box-body">Hi!</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-default">
                <div class="box-heading">Title</div>
                <div class="box-body">Hi!</div>
            </div>
        </div>
    </div>

    <!-- SECTION TYPOGRAPHIE -->
    <div class="section-header">
        Typographie
    </div>

    <h1>H1 Headline</h1>
    <h2>H2 Headline</h2>
    <h3>H3 Headline</h3>
    <h4>H4 Headline</h4>
    <h5>H5 Headline</h5>
    <h6>H6 Headline</h6>

    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed
        diam nonumy eirmod tempor invidunt ut labore et dolore
        magna aliquyam erat, sed diam voluptua. At vero eos et accu-
        sam et justo duo dolores et ea rebum.
    </p>

    <div>
        <a href="#">Link 1</a>
        <a href="#">Link 2</a>
        <a href="#">Link 3</a>
    </div>


    <!-- SECTION ICONS -->
    <div class="section-header">
        Flash Messages
    </div>
    <div class="alert alert-success">
        <p>Success!</p>
    </div>
    <div class="alert alert-warning">
        <p>Warning!</p>
    </div>
    <div class="alert alert-danger">
        <p>Danger!</p>
    </div>
    <div class="alert alert-info">
        <p>Info!</p>
    </div>


    <!-- SECTION ICONS -->
    <div class="section-header">
        Icons
    </div>
</div>