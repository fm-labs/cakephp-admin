<?php
$this->loadHelper('Backend.Chosen');
?>
<div class="index">

    <div class="row">
        <div class="col-md-12">
            <?= $this->cell('Backend.DataTable', [$this->get('dataTable'), $this->get('result')->toArray()]); ?>
        </div>
    </div>

    <?php if (\Cake\Core\Configure::read('debug')): ?>
    <small><?php echo h(__FILE__); ?></small>
    <?php endif; ?>
</div>
