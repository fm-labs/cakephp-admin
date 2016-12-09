<?php $this->Breadcrumbs->add(__('Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend', 'Menus')); ?>
<div class="index">
    <h2>Registered Backend Menus</h2>

    <?php var_dump($this->get('menus')); ?>
</div>