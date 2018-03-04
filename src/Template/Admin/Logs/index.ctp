<?php $this->loadHelper('Bootstrap.Tabs'); ?>
<?php $this->Breadcrumbs->add(__d('backend','Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend', 'Logs')); ?>
<div class="index">

    <?php $this->Tabs->create(); ?>
    <?php $this->Tabs->add(__d('backend', 'LogViewer')); ?>
	<h2><?= __d('backend', 'LogViewer'); ?></h2>
	<table class="ui table striped">
        <thead>
            <tr>
                <th><?= __d('backend', 'Logfile');?></th>
                <th><?= __d('backend', 'Filesize');?></th>
                <th><?= __d('backend', 'Last modified');?></th>
                <th><?= __d('backend', 'Last access');?></th>
                <th class="actions"><?= __d('backend', 'Actions'); ?></th>
            </tr>
        </thead>
        <?php foreach ($this->get('files') as $file):?>
            <tr>
                <td><?= $this->Html->link($file['name'], ['action' => 'view', $file['id']]); ?></td>
                <td><?= $this->Number->toReadableSize($file['size']); ?></td>
                <td><?= $this->Time->timeAgoInWords($file['last_modified']); ?></td>
                <td><?= $this->Time->timeAgoInWords($file['last_access']); ?></td>

                <td class="actions">
                    <?= $this->Html->link(__d('backend','View'), ['action' => 'view', $file['id']]) ?>
                    <?= $this->Ui->link(
                        __d('backend','Clear'),
                        ['action' => 'clear', $file['id']],
                        ['class' => 'item', 'data-icon' => 'trash']
                    ) ?>
                    <?= $this->Ui->postLink(
                        __d('backend','Delete'),
                        ['action' => 'delete', $file['id']],
                        ['class' => 'item', 'data-icon' => 'trash', 'confirm' => __d('backend','Are you sure you want to delete {0}?', $file['name'])]
                    ) ?>
                </td>
            </tr>
        <?php endforeach; ?>
	</table>


    <?php $this->Tabs->add(__d('backend', 'Log Rotation')); ?>
	<h2><?= __d('backend', 'Log Rotation'); ?></h2>
	<table class="ui table striped">
        <thead>
            <tr>
                <th><?= __d('backend', 'Alias');?></th>
                <th><?= __d('backend', 'Path');?></th>
                <th><?= __d('backend', 'Keep');?></th>
                <th><?= __d('backend', 'Schedule');?></th>
                <th><?= __d('backend', 'Compress');?></th>
                <th><?= __d('backend', 'Compress Delay');?></th>
                <th><?= __d('backend', 'Rotate Empty');?></th>
                <th><?= __d('backend', 'Email To');?></th>
                <th class="actions"><?= __d('backend', 'Actions'); ?></th>
            </tr>
        </thead>
		<?php foreach ($this->get('logRotation') as $alias => $config):?>
            <tr>
                <td><?= $alias; ?></td>
                <td><?= $config['path']; ?></td>
                <td><?= $config['keep']; ?></td>
                <td><?= $config['schedule']; ?></td>
                <td><?= $config['compress']; ?></td>
                <td><?= $config['compress_delay']; ?></td>
                <td><?= $config['rotate_empty']; ?></td>
                <td><?= $config['email_to']; ?></td>
                <td class="actions">
                    <ul>
                    <li><?= $this->Html->link('Rotate', ['action' => 'rotate', $alias])?></li>
                    <li><?= $this->Html->link('Force', ['action' => 'rotate', $alias, 'force'=>true]); ?></li>
                    </ul>
                </td>
            </tr>
		<?php endforeach; ?>
	</table>

    <?php $this->Tabs->add(__d('backend','Debug'), ['debugOnly' => true]); ?>
	<?php debug($this->get('files')); ?>

    <?php echo $this->Tabs->render(); ?>
</div>