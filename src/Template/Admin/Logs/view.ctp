<?php $this->Html->addCrumb(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('backend', 'Logs'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(h($logFile)); ?>
<div class="view">
	<h1><?php echo h($logFile); ?></h1>
	
	<div class="actions">
		<ul class="actions">
			<li><?php echo $this->Html->link(__d('backend', 'Go back'), ['action' => 'index']); ?></li>
			<li><?php echo $this->Html->link(__d('backend', 'Clear'), ['action' => 'clear', $logFile]); ?></li>
			<li><?php echo $this->Html->link(__d('backend', 'Delete'), ['action' => 'delete', $logFile]); ?></li>
		</ul>
	</div>
	
	<div style="margin: 10px 0;">
        <label for="log">Log</label>
		<textarea name="log" style="width:98%; height:90%; min-height: 500px;"><?= $this->get('log'); ?></textarea>
	</div>
</div>