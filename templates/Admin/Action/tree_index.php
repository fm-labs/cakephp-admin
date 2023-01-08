<?php
;
$this->loadHelper('Admin.DataTable');

//$this->Toolbar->addLink(__d('admin','Add'), ['action' => 'add'], ['class' => 'add']);
?>
<div class="index">
    <div class="box">
        <div class="box-body">
            <?php //echo $this->cell('Admin.DataTable', [$this->get('dataTable'), $this->get('result')->toArray()]); ?>
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
