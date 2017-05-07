<div class="dashboard-item">
    <h3>Systeminfo</h3>
    <dl class="dl-horizontal">
        <dt>Backend Version</dt>
        <dd><?= h(\Backend\Lib\Backend::version()); ?></dd>
        <dt>Cake PHP Version</dt>
        <dd><?= h(\Cake\Core\Configure::version()); ?></dd>
    </dl>
    <?= $this->Html->link(__('Open system info'), ['plugin' => 'Backend', 'controller' => 'System', 'action' => 'index']); ?>
</div>