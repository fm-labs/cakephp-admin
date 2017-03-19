<?php
use Cake\Core\Configure;

?>
<?php $this->Breadcrumbs->add(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('Systeminfo')); ?>
<div>
	<dl>
		<dt><?php echo __d('backend', "PHP Version")?></dt>
		<dd><?php echo phpversion();?></dd>
	
		<dt><?php echo __d('backend', "Cake Version");?></dt>
		<dd><?php echo Configure::version();?></dd>
		
		<dt><?php echo __d('backend', "Magic quotes");?></dt>
		<dd><?php echo (get_magic_quotes_gpc()) ? __d('backend', 'Enabled') : __d('backend', 'Disabled') ;?></dd>
		
		<dt><?php echo __d('backend', "User Agent");?></dt>
		<dd><?php echo env('HTTP_USER_AGENT') ;?></dd>
		
		<dt><?php echo __d('backend', "Client IP");?></dt>
		<dd><?php echo $this->request->clientIp();?></dd>
		
		<dt><?php echo __d('backend', "PHP Info");?></dt>
		<dd><?php echo $this->Html->link(__d('backend', 'Open'), array('action' => 'php'));?></dd>
		
		<dt><?php echo __d('backend', "Date & Time Info");?></dt>
		<dd><?php echo $this->Html->link(__d('backend', 'Open'), array('action' => 'datetime'));?></dd>
	
		<dt><?php echo __d('backend', "Plugins");?></dt>
		<dd><?php echo $this->Html->link(__d('backend', 'Open'), array('action' => 'plugins'));?></dd>
		
		<dt><?php echo __d('backend', "Globals");?></dt>
		<dd><?php echo $this->Html->link(__d('backend', 'Open'), array('action' => 'globals'));?></dd>
		
		<dt><?php echo __d('backend', "Session");?></dt>
		<dd><?php echo $this->Html->link(__d('backend', 'Open'), array('action' => 'session'));?></dd>
		
		<dt><?php echo __d('backend', "Config");?></dt>
		<dd><?php echo $this->Html->link(__d('backend', 'Open'), array('action' => 'config'));?></dd>

		<dt><?php echo __d('backend', "Routes");?></dt>
		<dd><?php echo $this->Html->link(__d('backend', 'Open'), array('action' => 'routes'));?></dd>

		<dt><?php echo __d('backend', "Menus");?></dt>
		<dd><?php echo $this->Html->link(__d('backend', 'Open'), array('action' => 'menus'));?></dd>

	</dl>


</div>