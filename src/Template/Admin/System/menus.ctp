<?php $this->Html->addCrumb(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Systeminfo'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('backend', 'Menus')); ?>
<div class="index">
    <h2>Registered Backend Menus</h2>

    <?php var_dump($this->get('menus')); ?>
</div>