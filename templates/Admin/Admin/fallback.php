<?php
$this->setLayout('auth');
?>
<div class="container">
    <h1>Uuups, this route is not connected :/</h1>

    <div>
        Path: <?php echo h($this->getRequest()->getUri()->getPath()); ?>
    </div>
</div>
