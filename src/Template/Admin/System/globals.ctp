<?php $this->Breadcrumbs->add(__d('backend','Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend','Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend', 'Globals')); ?>
<div>
	<h2><?php echo __d('backend', 'CAKEPHP Globals'); ?></h2>
	<table class="ui table striped">
    <?php foreach($globals as $global):?>
        <tr>
            <td><?php echo $global; ?>&nbsp;</td>
            <td><?php echo constant($global);?>&nbsp;</td>
        </tr>
    <?php endforeach;?>
	</table>
</div>