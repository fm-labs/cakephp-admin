<?php
use Backend\Lib\BackendNav;

$this->loadHelper('Bootstrap.Nav');
?>
<div class="be-sidebar">

    <?php echo $this->Nav->create(['class' => '', 'items' => BackendNav::getMenu('app')])->render(); ?>
    <?php echo $this->Nav->create(['class' => '', 'items' => BackendNav::getMenu('plugins')])->render(); ?>
    <?php echo $this->Nav->create(['class' => '', 'items' => BackendNav::getMenu('system')])->render(); ?>



</div>