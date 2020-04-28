<div class="dashboard-item">
    <h3>Systeminfo</h3>
    <dl class="dl-horizontal">
        <dt>Admin Version</dt>
        <dd><?= h(\Admin\Admin::version()); ?></dd>
        <dt>Cake PHP Version</dt>
        <dd><?= h(\Cake\Core\Configure::version()); ?></dd>
    </dl>
    <?= $this->Html->link(__d('admin','Open system info'), ['plugin' => 'Admin', 'controller' => 'System', 'action' => 'index']); ?>
</div>