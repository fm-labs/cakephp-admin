<?php $this->Breadcrumbs->add(__d('backend','Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend','Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend','Session')); ?>
<div class="view">
	<h2>Sessioninformation</h2>
    <?php echo $this->element('Backend.array_to_table', ['data' => $this->get('session')]); ?>
</div>