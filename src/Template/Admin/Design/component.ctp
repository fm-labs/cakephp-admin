<?php $this->extend('base'); ?>

<!-- SECTION TOOLTIP -->
<div class="section-header">
    Tooltip
</div>

<span data-toggle="tooltip" title="This is a tooltip">Span Tooltip</span> |
<a data-toggle="tooltip" title="This is a tooltip">Link Tooltip</a>
<br />
<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="left" title="Tooltip on left">Tooltip on left</button>
<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Tooltip on top">Tooltip on top</button>
<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">Tooltip on bottom</button>
<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="right" title="Tooltip on right">Tooltip on right</button>



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
    });
    $('#test-js-confirm').click(function(ev) {

        var confirmed = confirm("Confirm?");
        console.log("CONFIRM RESULT", confirmed);

        ev.stopPropagation();
        return false;
    });
</script>
<?php $this->end(); ?>

