<?php $this->Breadcrumbs->add(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend', 'Plugins')); ?>
<div>
	<h2><?php echo __d('backend', 'Plugins'); ?></h2>
	<?= $this->element('Backend.array_to_table', ['data' => $this->get('plugins')]); ?>
</div>