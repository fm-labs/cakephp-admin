<?php
$this->loadHelper('Backend.Chosen');
$this->loadHelper('Backend.DataTable');
?>
<div class="index">

    <div class="row">
        <div class="col-md-12">
            <?php //echo $this->cell('Backend.DataTable', [$this->get('dataTable'), $this->get('result')]); ?>
            <?php
            $this->DataTable->create($this->get('dataTable'), $this->get('result'));
            echo $this->DataTable->renderAll();
            ?>
        </div>
    </div>

    <?php if (\Cake\Core\Configure::read('debug')): ?>
    <small><?php echo h(__FILE__); ?></small>
    <?php endif; ?>
</div>
