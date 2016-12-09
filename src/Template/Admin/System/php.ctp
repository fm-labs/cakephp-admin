<?php $this->Breadcrumbs->add(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend', 'PHP Info')); ?>

<div class="system phpinfo">
	<?php echo $phpinfo; ?>
</div>
