<?php $this->Html->addCrumb(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Systeminfo'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('backend', 'Plugins')); ?>
<div>
	<h2><?php echo __d('backend', 'Plugins'); ?></h2>
	<?= $this->element('Backend.array_to_table', ['data' => $this->get('plugins')]); ?>
</div>