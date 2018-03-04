<?php $this->Breadcrumbs->add(__d('backend','Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend','Plugins'), ['action' => 'index']); ?>
<?php $this->loadHelpers('Backend.Ui'); ?>
<div>
	<h2><?php echo __d('backend', 'Plugins'); ?></h2>

	<table class="table">
		<tr>
			<th>Name</th>
			<th>Loaded</th>
			<th>Registered</th>
			<th>Handler Loaded</th>
			<th>Handler Enabled</th>
			<th>Actions</th>
		</tr>
		<?php foreach ((array) $this->get('plugins') as $pluginName => $info): ?>
		<tr>
			<td><?= h($info['name']); ?></td>
			<td><?= $this->Ui->statusLabel($info['loaded']); ?></td>
			<td><?= $this->Ui->statusLabel($info['registered']); ?></td>
			<td><?= $this->Ui->statusLabel($info['handler_loaded']); ?></td>
			<td><?= $this->Ui->statusLabel($info['handler_enabled']); ?></td>
			<td>
				<?= $this->Html->link('Settings', ['action' => 'settings', $info['name']]); ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>