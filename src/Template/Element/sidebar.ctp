<?php
use Backend\Lib\BackendNav;

$backendNavMenu = BackendNav::getMenu();
$this->loadHelper('Bootstrap.Nav');
?>
<div class="be-sidebar">

    <?php echo $this->Nav->create(['class' => '', 'items' => $backendNavMenu])->render(); ?>

</div>