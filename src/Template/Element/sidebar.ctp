<?php
use Cake\Core\Configure;
?>
<div id="backend-admin-sidebar" class="ui left vertical visible overlay sidebar menu">
    <a class="item">
        <i class="home icon"></i>
        Home
    </a>
    <?php echo $this->fetch('backend-sidebar'); ?>
</div>