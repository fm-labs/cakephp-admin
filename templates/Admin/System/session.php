<?php $this->Breadcrumbs->add(__d('admin', 'Admin'), ['controller' => 'Admin', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Session')); ?>
<div class="view">
    <h2><?= __d('admin', 'Session Info'); ?></h2>
    <?php echo $this->element('Admin.array_to_tablelist', ['data' => $this->get('session')]); ?>
</div>