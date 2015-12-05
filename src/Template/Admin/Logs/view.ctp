<?php $this->Html->addCrumb(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('backend', 'Logs'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(h($logFile)); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Logs')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('Clear'),
    ['action' => 'clear'],
    ['icon' => 'trash outline']
) ?>
<?= $this->Toolbar->addLink(
    __('Delete'),
    ['action' => 'index'],
    ['icon' => 'trash']
) ?>
<div class="view backend logs">
	<h2 class="ui header">
        <?php echo h($logFile); ?>
    </h2>

	<div class="log-text">
		<textarea name="log" style="width:98%; height:90%; min-height: 500px;"><?= $this->get('log'); ?></textarea>
	</div>
</div>