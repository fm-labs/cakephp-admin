<?php $this->Breadcrumbs->add(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('Config')); ?>
<div class="view">
    <h2>Config</h2>
    <?php echo $this->element('Backend.array_to_table', ['data' => $this->get('config')]); ?>
</div>