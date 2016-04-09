<?php
use Cake\Core\Configure;

?>
<?php $this->Html->addCrumb(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Systeminfo')); ?>
<div>
	<h1><?php echo __d('backend', "System "); ?></h1>

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
		<dd><?php echo $this->Html->link(__d('backend', 'Open'), array('action' => 'php'), ['class' => 'link-frame', 'title' => 'PHP Info']);?></dd>
		
		<dt><?php echo __d('backend', "Date & Time Info");?></dt>
		<dd><?php echo $this->Html->link(__d('backend', 'Open'), array('action' => 'datetime'), ['class' => 'link-modal']);?></dd>
	
		<dt><?php echo __d('backend', "Plugins");?></dt>
		<dd><?php echo $this->Html->link(__d('backend', 'Open'), array('action' => 'plugins'), ['class' => 'link-frame-modal']);?></dd>
		
		<dt><?php echo __d('backend', "Globals");?></dt>
		<dd><?php echo $this->Html->link(__d('backend', 'Open'), array('action' => 'globals'));?></dd>
		
		<dt><?php echo __d('backend', "Session");?></dt>
		<dd><?php echo $this->Html->link(__d('backend', 'Open'), array('action' => 'session'));?></dd>
		
		<dt><?php echo __d('backend', "Config");?></dt>
		<dd><?php echo $this->Html->link(__d('backend', 'Open'), array('action' => 'config'));?></dd>
	
	</dl>


</div>