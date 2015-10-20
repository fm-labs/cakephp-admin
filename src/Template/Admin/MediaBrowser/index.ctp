<?php use Cake\Core\Configure;
$this->loadHelper('Backend.Ui');
?>
<?= $this->Toolbar->addLink('New Folder', ['action' => 'dir_create', 'path' => $currentPath], ['class' => '', 'icon' => 'folder']) ?>

<?= $this->Toolbar->addLink('New File', ['action' => 'file_create', 'path' => $currentPath], ['class' => '', 'icon' => 'file']); ?>

<?= $this->Toolbar->addLink('Upload Files', ['action' => 'upload', 'path' => $currentPath], ['class' => '', 'icon' => 'file']); ?>


<div class="file-manager index">

    <div class="ui grid">
        <div class="row">
            <div class="three wide column">
                <div class="ui vertical fluid tabular menu">
                <?php foreach (Configure::read('Media') as $mediaId => $config): ?>
                <?php $class = ($mediaId == $cfg) ? 'item active' : 'item'; ?>
                <?= $this->Html->link(
                        $config['label'],
                        ['action' => 'browse', 'config' => $mediaId],
                        ['class' => $class]
                ); ?>
                <?php endforeach; ?>
                </div>
            </div>
            <div class="thirteen wide column">


                <h3 class="ui header">
                    <i class="folder open icon"></i>
                    <?= h($currentPath); ?>
                </h3>
                <div class="ui _attached _segment">

                    <!--
                    <div class="ui secondary menu">
                        <div class="item">
                            <?= $this->Ui->link('New Folder', ['action' => 'dir_create', 'path' => $currentPath], ['class' => '', 'icon' => 'folder']); ?>
                        </div>
                        <div class="item">
                            <?= $this->Ui->link('New File', ['action' => 'file_create', 'path' => $currentPath], ['class' => '', 'icon' => 'file']); ?>
                        </div>
                        <div class="item">
                            <?= $this->Ui->link('Upload Files', ['action' => 'upload', 'path' => $currentPath], ['class' => '', 'icon' => 'file']); ?>
                        </div>
                    </div>
                    -->

                    <table class="ui table striped">
                        <!--
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                        -->
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <i class="level up icon"></i>
                                <?= $this->Html->link('Parent', [
                                    'action' => 'index',
                                    'path' => $this->get('parentPath'),
                                    'config' => $cfg
                                ]); ?>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php foreach ($this->get('directories') as $dir): ?>
                            <tr>
                                <td class="collapsing">
                                    <i class="folder outline icon"></i>
                                    <?= $this->Ui->link(
                                        basename($dir),
                                        ['action' => 'index', 'path' => $dir, 'config' => $cfg],
                                        ['title' => $dir]
                                    ); ?>
                                </td>
                                <td>
                                    <?= $this->Ui->link('Open', ['action' => 'dir_open', 'path' => $dir, 'config' => $cfg]); ?>
                                    <?= $this->Ui->link('Copy', ['action' => 'dir_copy', 'path' => $dir, 'config' => $cfg]); ?>
                                    <?= $this->Ui->link('Move', ['action' => 'dir_move', 'path' => $dir, 'config' => $cfg]); ?>
                                    <?= $this->Ui->link('Delete', ['action' => 'dir_delete', 'path' => $dir, 'config' => $cfg]); ?>
                                    <?= $this->Ui->link('Info', ['action' => 'dir_info', 'path' => $dir, 'config' => $cfg]); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php foreach ($this->get('files') as $file): ?>
                            <tr>
                                <td class="collapsing">
                                    <i class="file outline icon"></i>
                                    <span title="<?= h($file); ?>"><?= h(basename($file)); ?></span>
                                </td>
                                <td>
                                    <?= $this->Ui->link('Open', ['action' => 'file_open', 'path' => $file]); ?>
                                    <?= $this->Ui->link('Copy', ['action' => 'file_copy', 'path' => $file]); ?>
                                    <?= $this->Ui->link('Move', ['action' => 'file_move', 'path' => $file]); ?>
                                    <?= $this->Ui->link('Delete', ['action' => 'file_delete', 'path' => $file]); ?>
                                    <?= $this->Ui->link('Info', ['action' => 'file_info', 'path' => $file]); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="ui divider"></div>

                </div> <!-- #segment -->
            </div> <!-- #column -->

        </div>
    </div>

    <?php debug($config); ?>
    <?php debug($currentPath); ?>
    <?php debug($directories); ?>
    <?php debug($files); ?>
</div>