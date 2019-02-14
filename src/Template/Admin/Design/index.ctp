<?php $this->extend('base'); ?>


<!-- SECTION ALERT -->
<div class="section-header">
    Flash Messages
</div>

<div class="" style="margin-bottom: 10px;">
    <?= $this->Html->link('Flash success', ['flash' => 'success'], ['class' => 'btn btn-default']); ?>
    <?= $this->Html->link('Flash warning', ['flash' => 'warning'], ['class' => 'btn btn-default']); ?>
    <?= $this->Html->link('Flash error', ['flash' => 'error'], ['class' => 'btn btn-default']); ?>
    <?= $this->Html->link('Flash info', ['flash' => 'info'], ['class' => 'btn btn-default']); ?>
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

