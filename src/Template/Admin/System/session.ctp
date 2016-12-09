<?php $this->Breadcrumbs->add(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('Session')); ?>
<div class="view">
	<h2>Sessioninformation</h2>
    <?php echo $this->element('Backend.array_to_table', ['data' => $this->get('session')]); ?>
</div>