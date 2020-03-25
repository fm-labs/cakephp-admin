<?php
if (!isset($toolbar) || $this->get('_no_toolbar')) {
    return;
}
?>
<nav class="toolbar">
    <?php echo $toolbar; ?>
</nav>
