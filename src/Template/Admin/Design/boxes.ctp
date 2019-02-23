<?php $this->extend('base'); ?>


<!-- SECTION BOXES -->
<div class="section-header">
    Box
</div>
<?= $this->element('Design/boxes_row', ['params' => ['headerHtml' => 'Box']]); ?>
<?= $this->element('Design/boxes_row', ['params' => ['headerClass' => 'with-border', 'headerHtml' => 'Header with Border']]); ?>
<?= $this->element('Design/boxes_row', ['params' => ['boxClass' => 'box-solid', 'headerHtml' => 'Box Solid']]); ?>
<?= $this->element('Design/boxes_row', ['params' => ['collapse' => true, 'collapsed' => false, 'headerHtml' => 'Collapsable']]); ?>
<?= $this->element('Design/boxes_row', ['params' => ['collapse' => true, 'collapsed' => true, 'headerHtml' => 'Collapsed']]); ?>


<!-- SECTION BOXES -->
<div class="section-header">
    Info Box
</div>
<?= $this->element('Design/infoboxes_row', ['params' => []]); ?>
<?= $this->element('Design/infoboxes2_row', ['params' => []]); ?>

<!-- SECTION BOXES -->
<div class="section-header">
    Small Box
</div>
<?= $this->element('Design/smallboxes_row', ['params' => []]); ?>
<?= $this->element('Design/smallboxes_row', ['params' => ['footer' => true]]); ?>