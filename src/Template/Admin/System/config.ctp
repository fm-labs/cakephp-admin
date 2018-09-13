<?php $this->Breadcrumbs->add(__d('backend','Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend','Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend','Config')); ?>
<div class="view">
    <h2>Config</h2>
    <?php echo $this->element('Backend.array_to_tablelist', ['data' => $this->get('config')]); ?>
</div>