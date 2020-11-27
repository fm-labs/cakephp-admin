<?php $this->Breadcrumbs->add(__d('admin', 'Admin'), ['controller' => 'Admin', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Config')); ?>
<div class="view container">
    <?= $this->Box->create(__("Configuration"), ['class' => 'box-solid']); ?>
    <?php echo $this->element('Admin.array_to_tablelist', ['data' => $this->get('config')]); ?>
    <?= $this->Box->render(); ?>
</div>
