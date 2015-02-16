<?php $this->Html->addCrumb(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Systeminfo'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('backend', 'Date & Time')); ?>
<div>
	<h2><?php echo __d('backend', 'Date & Time'); ?></h2>
	<table class="ui table">
    <?php foreach($data as $key => $val): ?>
        <tr>
            <td><?php echo $key; ?>&nbsp;</td>
            <td><?php echo $val;?>&nbsp;</td>
        </tr>
    <?php endforeach;?>
	</table>
</div>