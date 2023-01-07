<?php
$this->loadHelper('Bootstrap.Nav');
?>

<?= $this->Nav->create($primary = $primary ?? [], ['class' => 'nav flex-column']); ?>
<?= $this->Nav->create($secondary = $secondary ?? [], ['class' => 'nav flex-column']); ?>

<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
    <span>System</span>
</h6>
<?= $this->Nav->create($system = $system ?? [], ['class' => 'nav flex-column']); ?>


<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
    <span>Developer</span>
</h6>
<?= $this->Nav->create($developer = $developer ?? [], ['class' => 'nav flex-column']); ?>
