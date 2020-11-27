<?php $this->Breadcrumbs->add(__d('admin', 'Admin'), ['controller' => 'Admin', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'PHP Info')); ?>
<?php $phpinfo = $this->get('phpinfo'); ?>
<div class="system phpinfo view container">
    <?= $this->Box->create("PHP Info", ['class' => 'box-solid']); ?>
    <?php echo $phpinfo; ?>
    <?= $this->Box->render(); ?>
</div>
<script>
    $('.phpinfo').find('table').addClass('table');
</script>
