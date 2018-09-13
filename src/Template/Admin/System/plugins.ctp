<?php $this->Breadcrumbs->add(__d('backend','Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend','Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend', 'Plugins')); ?>
<div>
	<h2><?php echo __d('backend', 'Plugins'); ?></h2>
	<?= $this->element('Backend.array_to_tablelist', ['data' => $this->get('plugins')]); ?>
</div>