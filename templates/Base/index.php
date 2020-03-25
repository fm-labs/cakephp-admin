<?php
$this->assign('title', $this->get('title'));
$this->assign('heading', $this->get('heading'));
?>
<div class="index">
    <?= $this->fetch('content'); ?>
</div>
