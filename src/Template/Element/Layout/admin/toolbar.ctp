<?php
if ($this->get('_no_toolbar')) {
    return;
}

$toolbar = (isset($toolbar)) ? $toolbar : '';
?>
<nav class="toolbar">
    <?php echo $toolbar; ?>
</nav>
