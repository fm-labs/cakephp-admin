<?php $this->Html->addCrumb(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('backend', 'Logs'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(h($logFile)); ?>
<div class="actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <?= $this->Ui->link(
                __('List {0}', __('Logs')),
                ['action' => 'index'],
                ['class' => 'item', 'icon' => 'list']
            ) ?>
            <?= $this->Ui->link(
                __('Clear'),
                ['action' => 'clear'],
                ['class' => 'item', 'icon' => 'trash outline']
            ) ?>
            <?= $this->Ui->link(
                __('Delete'),
                ['action' => 'index'],
                ['class' => 'item', 'icon' => 'remove']
            ) ?>
            <div class="ui dropdown item">
                <i class="dropdown icon"></i>
                <i class="setting icon"></i>More Actions
                <div class="menu">
                    <span class="item">No Actions</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>
<div class="view">
	<h2 class="ui top attached header">
        <?php echo h($logFile); ?>
    </h2>

	<div class="ui attached segment">
		<textarea name="log" style="width:98%; height:90%; min-height: 500px;"><?= $this->get('log'); ?></textarea>
	</div>
</div>