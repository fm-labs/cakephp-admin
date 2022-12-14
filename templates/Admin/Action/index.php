<?php
//;
$this->loadHelper('Sugar.Box');
$this->loadHelper('Sugar.DataTable');
$this->extend('Admin./Base/index');
//$this->Toolbar->addLink(__('Add'), ['action' => 'add'], ['data-icon' => 'plus']);
?>

<!-- Table stats -->
<?php
if (isset($tableStats)) {
    echo $this->element('Admin.Action/Index/stats', ['stats' => $tableStats]);
}
?>

<!-- Filter form -->
<?php if ($this->get('filters')) : ?>
    <?= $this->element('Admin.filterbar', ['filters' => $this->get('filters')]); ?>
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
