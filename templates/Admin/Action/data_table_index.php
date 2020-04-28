<?php
$this->loadHelper('Admin.DataTableJs');
?>
<div class="index">
    <?php
    echo $this->DataTableJs->create($this->get('dataTable'))
        ->setData($this->get('result'))
        ->render();
    ?>
    <?php debug($this->get('dataTable')); ?>
</div> <!-- #dt-container -->