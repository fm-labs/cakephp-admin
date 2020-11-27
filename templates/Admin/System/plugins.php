<?php $this->Breadcrumbs->add(__d('admin', 'Admin'), ['controller' => 'Admin', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Plugins')); ?>
<div class="view container">
    <?= $this->Box->create(__("Plugins"), ['class' => 'box-solid']); ?>
    <?= $this->element('Admin.array_to_tablelist', ['data' => $this->get('plugins')]); ?>
    <?= $this->Box->render(); ?>
</div>