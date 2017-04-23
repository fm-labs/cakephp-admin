<?php $this->Breadcrumbs->add(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend', 'Logs'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(h($logFile)); ?>
<?php $this->Toolbar->addLink(
    __('List {0}', __('Logs')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __('Clear'),
    ['action' => 'clear'],
    ['data-icon' => 'trash outline']
) ?>
<?php $this->Toolbar->addLink(
    __('Delete'),
    ['action' => 'index'],
    ['data-icon' => 'trash']
) ?>
<div class="view backend logs">
	<h2 class="ui header">
        <?php echo h($logFile); ?>
    </h2>

	<div class="log-text">
		<textarea name="log" style="width:98%; height:90%; min-height: 500px;"><?= $this->get('log'); ?></textarea>

        <?= $this->Html->link(__('Load more logs'), ['action' => 'view', $logFile, 'page' => $page + 1]); ?>
	</div>
</div>