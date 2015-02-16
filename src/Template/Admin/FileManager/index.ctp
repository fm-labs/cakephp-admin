<div class="file-manager index">
    <h3 class="ui top attached header">
        <i class="folder open icon"></i>
        <?= h($d->path()); ?>
    </h3>
    <div class="ui attached segment">

        <div class="ui secondary menu">
            <div class="item">
                <?= $this->Ui->link('New Folder', '#', ['class' => '', 'icon' => 'folder']); ?>
            </div>
            <div class="item">
                <?= $this->Ui->link('New File', '#', ['class' => '', 'icon' => 'file']); ?>
            </div>
        </div>

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
                    <?= $this->Html->link('Parent', ['action' => 'index', 'd' => $d->parent()]); ?>
                </td>
                <td>&nbsp;</td>
            </tr>
            <?php foreach ($d->directories() as $dir): ?>
            <tr>
                <td class="collapsing">
                    <i class="folder outline icon"></i>
                    <?= $this->Ui->link($dir, ['action' => 'index', 'd' => $dir->path()]); ?>
                </td>
                <td>
                    &nbsp;
                </td>
            </tr>
            <?php endforeach; ?>

            <?php foreach ($d->files() as $file): ?>
                <tr>
                    <td class="collapsing">
                        <i class="file outline icon"></i>
                        <?= h($file); ?>
                    </td>
                    <td>
                        <?= $this->Ui->link('Open', '#'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>