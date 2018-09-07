<?php
use Cake\Core\Configure;

$this->loadHelper('Bootstrap.Tabs');
?>
<?php //$this->Breadcrumbs->add(__d('backend','Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php //$this->Breadcrumbs->add(__d('backend','Systeminfo')); ?>
<div class="index">

	<?php $this->Tabs->create(); ?>
	<?php $this->Tabs->add(__d('backend','System')); ?>

	<h2>PHP</h2>
	<dl class="dl-horizontal">

		<dt>PHP Version</dt>
		<dd><?= phpversion();?></dd>

		<dt>Magic quotes</dt>
		<dd><?= (get_magic_quotes_gpc()) ? __d('backend', 'Enabled') : __d('backend', 'Disabled') ;?></dd>

		<dt>Memory limit</dt>
		<dd><?= ini_get('memory_limit'); ?></dd>

		<dt>Max execution time</dt>
		<dd><?= ini_get('max_execution_time'); ?></dd>

		<dt>Max input time</dt>
		<dd><?= ini_get('max_input_time'); ?></dd>

		<dt>Max input vars</dt>
		<dd><?= ini_get('max_input_vars'); ?></dd>

		<dt>Post max size</dt>
		<dd><?= ini_get('post_max_size'); ?></dd>

		<dt>Safe mode</dt>
		<dd><?= ini_get('safe_mode'); ?></dd>

		<dt>Allow url fopen</dt>
		<dd><?= ini_get('allow_url_fopen'); ?></dd>

		<dt>Allow url include</dt>
		<dd><?= ini_get('allow_url_include'); ?></dd>

		<dt>File uploads</dt>
		<dd><?= ini_get('file_uploads'); ?></dd>

		<dt>Upload max filesize</dt>
		<dd><?= ini_get('upload_max_filesize'); ?></dd>


		<dt>Log errors</dt>
		<dd><?= ini_get('log_errors'); ?></dd>
	</dl>

	<h2>Date & Time</h2>
	<dl class="dl-horizontal">
		<dt>Default Timezone</dt>
		<dd><?= date_default_timezone_get();?></dd>

		<dt>Timezone</dt>
		<dd><?= ini_get('date.timezone');?></dd>
	</dl>

	<h2>CakePHP</h2>
	<dl class="dl-horizontal">
		<dt>Cake Version</dt>
		<dd><?= Configure::version();?></dd>
	</dl>


	<h2>Client</h2>
	<dl class="dl-horizontal">
		<dt>User Agent</dt>
		<dd><?= env('HTTP_USER_AGENT') ;?></dd>
		
		<dt>Client IP</dt>
		<dd><?= $this->request->clientIp();?></dd>
	</dl>

	<?php $this->Tabs->add(__d('backend','Config'), ['url' => ['action' => 'config']]); ?>
	<?php $this->Tabs->add(__d('backend','Plugins'), ['url' => ['action' => 'plugins']]); ?>
	<?php $this->Tabs->add(__d('backend','Routes'), ['url' => ['action' => 'routes']]); ?>
	<?php $this->Tabs->add(__d('backend','Globals'), ['url' => ['action' => 'globals']]); ?>
	<?php $this->Tabs->add(__d('backend','Session'), ['url' => ['action' => 'session']]); ?>
	<?php $this->Tabs->add(__d('backend','PHP Info'), ['url' => ['action' => 'php']]); ?>
	<?php echo $this->Tabs->render(); ?>
</div>