<?php $this->Breadcrumbs->add(__d('admin', 'Admin'), ['controller' => 'Admin', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Plugins'), ['action' => 'index']); ?>
<?php $this->loadHelper('Cupcake.Status'); ?>
<div class="index">
    <table class="table table-striped table-hover table-sm">
        <tr>
            <th>Name</th>
            <th>Version</th>
            <th>Loaded</th>
            <th>Path</th>
            <th>Class</th>
            <th>Bootstrap</th>
            <th>Routes</th>
            <th>Local</th>
            <th>Actions</th>
        </tr>
        <?php foreach ((array)$this->get('plugins') as $info) : ?>
        <tr>
            <td><?= $this->Html->link($info['name'], ['action' => 'view', $info['name']]); ?></td>
            <td><?= h($info['composer_name'] ?? 'local'); ?>:<?= h($info['version'] ?? 'dev'); ?></td>
            <td><?= $this->Status->boolean(!!$info['loaded']); ?></td>
            <td><?= $this->Status->display($info['path'] ?? "?"); ?></td>
            <td><?= $this->Status->display($info['instance']['class'] ?? "<span class='text-muted fst-italic'>Not loaded</span>"); ?></td>
            <td><?= $this->Status->boolean($info['instance']['bootstrap'] ?? false); ?></td>
            <td><?= $this->Status->boolean($info['instance']['routes'] ?? false); ?></td>
            <td><?= $this->Status->boolean($info['local'] ?? false); ?></td>

            <td class="actions">
                <?php if (!!$info['loaded']) : ?>
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
    </table>
</div>