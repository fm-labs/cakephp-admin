<?php
//$this->loadHelper('Backend.Chosen');
$this->loadHelper('Backend.Box');
$this->loadHelper('Backend.DataTable');
$this->extend('Backend./Base/index');
?>
<style>
    .filter-form-container {
        margin: 0.5em 0;
    }

    .filter-form-container button {
        vertical-align: top;
    }

    .filter-form-input {
        display: inline-block;
        width: 180px;
        max-width: 180px;
    }
</style>
<div class="index">

    <!-- Table stats -->
    <?php
    if (isset($tableStats)) {
        echo $this->element('Backend.Action/Index/stats', ['stats' => $tableStats]);
    }
    ?>

    <!-- Filter form -->
    <?php if ($this->get('filters')) : ?>
        <div class="filter-form-container" style="margin: 0.5em 0">
            <?= $this->Form->create(null, ['method' => 'GET', 'url' => ['action' => 'index'], 'templates' => [
                'inputContainer' => '<div class="filter-form-input">{{content}}</div>',
                'formGroup' => '<div>{{label}}{{help}}{{input}}{{error}}</div>',
                'label' => ''
            ]]); ?>
            <?= $this->Form->hidden('_form', ['value' => 'filter']); ?>
            <?php foreach ($this->get('filters') as $filter => $inputOptions) : ?>
                <?php $inputOptions['value'] = $this->request->query('_filter.' . $filter); ?>
                <?= $this->Form->input('_filter.' . $filter, $inputOptions); ?>
            <?php endforeach; ?>
            <?= $this->Form->button('<i class="fa fa-search"></i>', ['escape' => false, 'class' => 'btn btn-primary']); ?>
            <?= $this->Form->end(); ?>
        </div>
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
