<?php $this->Breadcrumbs->add(__d('backend', 'Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend', 'Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend', 'Plugins')); ?>
<div class="view">
    <?= $this->element('Backend.array_to_tablelist', ['data' => $this->get('plugins')]); ?>
</div>