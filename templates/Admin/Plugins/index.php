<?php $this->Breadcrumbs->add(__d('admin', 'Admin'), ['controller' => 'Admin', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Plugins'), ['action' => 'index']); ?>
<?php $this->loadHelpers('Admin.Ui'); ?>
<div class="index">
    <table class="table">
        <tr>
            <th>Name</th>
            <th>Loaded</th>
            <th>Class</th>
            <th>Path</th>
            <th>Bootstrap</th>
            <th>Routes</th>
            <th>Settings</th>
            <th>Actions</th>
        </tr>
        <?php foreach ((array)$this->get('plugins') as $pluginName => $info) : ?>
        <tr>
            <td><?= h($info['name']); ?></td>
            <td><?= $this->Ui->statusLabel($info['loaded']); ?></td>
            <td><?= $this->Ui->statusLabel($info['handler_class']); ?></td>
            <td><?= $this->Ui->statusLabel($info['path']); ?></td>
            <td><?= $this->Ui->statusLabel($info['handler_bootstrap']); ?></td>
            <td><?= $this->Ui->statusLabel($info['handler_routes']); ?></td>
            <td>
                <?php if ($info['configuration_url']) : ?>
                    <?= $this->Html->link('Configure', $info['configuration_url']); ?>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($info['handler_bootstrap']) : ?>
                    <?= $this->Html->link('Disable', ['controller' => 'Plugins', 'action' => 'disable', $info['name']]); ?>
                <?php else : ?>
                    <?= $this->Html->link('Enable', ['controller' => 'Plugins', 'action' => 'enable', $info['name']]); ?>
                <?php endif; ?>
                |
                <?= $this->Html->link('Uninstall', ['controller' => 'Plugins', 'action' => 'uninstall', $info['name']]); ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php foreach ((array)$this->get('installed') as $pluginName => $info) : ?>
            <tr>
                <td><?= h($info['name']); ?></td>
                <td><?= $this->Ui->statusLabel($info['loaded']); ?></td>
                <td><?= $this->Ui->statusLabel($info['handler_class']); ?></td>
                <td><?= $this->Ui->statusLabel($info['path']); ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                <?= $this->Html->link('Enable', ['controller' => 'Plugins', 'action' => 'enable', $info['name']]); ?>

                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>