<?php $this->Html->addCrumb(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Systeminfo'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Session')); ?>
<div class="view">
	<h2>Sessioninformation</h2>
    <?php echo $this->element('Backend.array_to_table', ['data' => $this->get('session')]); ?>
</div>