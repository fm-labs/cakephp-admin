<?php
$this->loadHelper('Bootstrap.Nav');
?>

<?= '' // $this->Nav->create($primary = $primary ?? [], ['class' => 'nav flex-column']); ?>
<?= $this->element('Admin.layout/admin/sidebar/sidebar_menu_item', ['menu' => $primary ?? []]); ?>

<?= '' // $this->Nav->create($secondary = $secondary ?? [], ['class' => 'nav flex-column']); ?>
<?= $this->element('Admin.layout/admin/sidebar/sidebar_menu_item', ['menu' => $secondary ?? []]); ?>

<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
    <span>System</span>
</h6>
<?= '' // $this->Nav->create($system = $system ?? [], ['class' => 'nav flex-column']); ?>
<?= $this->element('Admin.layout/admin/sidebar/sidebar_menu_item', ['menu' => $system ?? []]); ?>


<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
    <span>User</span>
</h6>
<?= '' // $this->Nav->create($user = $user ?? [], ['class' => 'nav flex-column']); ?>
<?= $this->element('Admin.layout/admin/sidebar/sidebar_menu_item', ['menu' => $user ?? []]); ?>


<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
    <span>Developer</span>
</h6>
<?= $this->element('Admin.layout/admin/sidebar/sidebar_menu_item', ['menu' => $developer ?? []]);
//$this->Nav->create($developer = $developer ?? [], ['class' => 'nav flex-column']);
?>
