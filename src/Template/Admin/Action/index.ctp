<?php
//$this->loadHelper('Backend.Chosen');
$this->loadHelper('Backend.Box');
$this->loadHelper('Backend.DataTable');
$this->extend('Backend./Base/index');
//$this->Toolbar->addLink(__('Add'), ['action' => 'add'], ['data-icon' => 'plus']);
?>
<div class="index">

    <!-- Table stats -->
    <?php
    if (isset($tableStats)) {
        echo $this->element('Backend.Action/Index/stats', ['stats' => $tableStats]);
    }
    ?>

    <!-- Filter form -->
    <?php if ($this->get('filters')) : ?>
        <?= $this->element('Backend.filterbar', ['filters' => $this->get('filters')]); ?>
    <?php endif; ?>

    <!-- Data table -->
    <?php $this->Box->create(false/*, ['class' => 'box']*/); ?>
    <?php
    $this->DataTable->create($this->get('dataTable'), $this->get('result'));
    echo $this->DataTable->renderAll();
    ?>
    <?php echo $this->Box->render(); ?>

    <?php if ($this->get('debug')) : ?>
        <?php $this->Box->create("Debug"); ?>
        <?php debug($this->get('result')->toArray()); ?>
        <?php echo $this->Box->render(); ?>
    <?php endif; ?>

    <?php if (\Cake\Core\Configure::read('debug')) : ?>
    <small><?php echo h(__FILE__); ?></small>
    <?php endif; ?>
</div>
