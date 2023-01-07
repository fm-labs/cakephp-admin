<?php
$this->loadHelper('Bootstrap.Tabs');
$this->assign('title', 'System Info')
?>
<?php $this->Breadcrumbs->add(__d('admin','Systeminfo')); ?>
<div class="index">
    <?php $this->Tabs->create(); ?>
    <?php $this->Tabs->add(__d('admin', 'Info'), $this->element('Admin.System/info')); ?>
    <?php $this->Tabs->add(__d('admin', 'Config'), $this->element('Admin.System/config')); ?>
    <?php $this->Tabs->add(__d('admin', 'Plugins'), $this->element('Admin.System/plugins')); ?>
    <?php $this->Tabs->add(__d('admin', 'Routes'), $this->element('Admin.System/routes')); ?>
    <?php $this->Tabs->add(__d('admin', 'Globals'), $this->element('Admin.System/globals')); ?>
    <?php $this->Tabs->add(__d('admin', 'Session'), $this->element('Admin.System/session')); ?>
    <?php $this->Tabs->add(__d('admin', 'PHP Info'), $this->element('Admin.System/phpinfo')); ?>
    <?php $this->Tabs->add(__d('admin', 'Date and TIme'), $this->element('Admin.System/datetime')); ?>
    <?php echo $this->Tabs->render(); ?>
</div>
