<?php $this->Html->addCrumb(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Systeminfo'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Config')); ?>
<div class="view">
    <h2>Config</h2>
    <?php echo $this->element('Backend.array_to_table', ['data' => $this->get('config')]); ?>
</div>