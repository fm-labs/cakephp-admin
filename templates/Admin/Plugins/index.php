<?php $this->Breadcrumbs->add(__d('admin', 'Admin'), ['controller' => 'Admin', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Plugins'), ['action' => 'index']); ?>
<?php $this->loadHelper('Cupcake.Status'); ?>
<div class="index">
    <table class="table table-striped table-hover table-sm">
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
            <td><?= $this->Status->boolean(!!$info['loaded']); ?></td>
            <td><?= $this->Status->display($info['handler_class']); ?></td>
            <td><?= $this->Status->display($info['path']); ?></td>
            <td><?= $this->Status->boolean(!!$info['handler_bootstrap']); ?></td>
            <td><?= $this->Status->boolean(!!$info['handler_routes']); ?></td>
            <td>
                <?php if ($info['configuration_url']) : ?>
                    <?= $this->Html->link('Configure', $info['configuration_url']); ?>
                <?php endif; ?>
            </td>
            <td class="actions">
                <?php if ($info['handler_bootstrap']) : ?>
                    <?= $this->Html->link('Disable',
                        ['controller' => 'Plugins', 'action' => 'disable', $info['name']],
                        ['class' => 'btn btn-xxs btn-secondary']
                    ); ?>
                <?php else : ?>
                    <?= $this->Html->link('Enable',
                        ['controller' => 'Plugins', 'action' => 'enable', $info['name']],
                        ['class' => 'btn btn-xxs btn-primary']
                    ); ?>
                <?php endif; ?>
                
                <?php //@todo Uninstall plugin //echo  $this->Html->link('Uninstall', ['controller' => 'Plugins', 'action' => 'uninstall', $info['name']]); ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="8"><span class="text-bold">Installed, but inactive plugins</span></td>
        </tr>
        <?php foreach ((array)$this->get('installed') as $pluginName => $info) : ?>
            <tr>
                <td><?= h($info['name']); ?></td>
                <td><?= $this->Status->boolean(!!$info['loaded']); ?></td>
                <td><?= $this->Status->boolean(!!$info['handler_class']); ?></td>
                <td><?= $this->Status->display($info['path']); ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="actions">
                    <?= $this->Html->link('Enable', ['controller' => 'Plugins', 'action' => 'enable', $info['name']],
                        ['class' => 'btn btn-xxs btn-primary']
                    ); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>