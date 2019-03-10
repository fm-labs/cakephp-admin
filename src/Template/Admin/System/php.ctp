<?php $this->Breadcrumbs->add(__d('backend','Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend','Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend', 'PHP Info')); ?>

<div class="system phpinfo view">
	<?php echo $phpinfo; ?>
</div>
<script>
	$('.phpinfo').find('table').addClass('table');
</script>