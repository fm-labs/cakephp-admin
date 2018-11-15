<?php $this->Breadcrumbs->add(__d('backend','Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend','Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend', 'Date & Time')); ?>
<div class="view">
	<h2><?php echo __d('backend', 'Date & Time'); ?></h2>
	<table class="table">
    <?php foreach($data as $key => $val): ?>
        <tr>
            <td><?php echo $key; ?>&nbsp;</td>
            <td><?php echo $val;?>&nbsp;</td>
        </tr>
    <?php endforeach;?>
	</table>
</div>