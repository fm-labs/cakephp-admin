<?php $this->Breadcrumbs->add(__d('admin','Admin'), ['controller' => 'Admin', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin','Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'PHP Info')); ?>

<div class="system phpinfo view">
	<?php echo $phpinfo; ?>
</div>
<script>
	$('.phpinfo').find('table').addClass('table');
</script>