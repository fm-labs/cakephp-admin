<?php $this->Breadcrumbs->add(__d('admin', 'Admin'), ['controller' => 'Admin', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Session')); ?>
<div class="view container">
    <?= $this->Box->create(__d('admin', 'Session Info'), ['class' => 'box-solid']); ?>
    <?php echo $this->element('Admin.array_to_tablelist', ['data' => $this->get('session')]); ?>
    <?= $this->Box->render(); ?>
</div>