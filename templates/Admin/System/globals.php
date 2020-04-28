<?php $this->Breadcrumbs->add(__d('admin', 'Admin'), ['controller' => 'Admin', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Globals')); ?>
<div class="view">
    <h2><?php echo __d('admin', 'CAKEPHP Globals'); ?></h2>
    <table class="table table-striped table-hover">
        <?php foreach ($globals as $global) : ?>
            <tr>
                <td><?php echo $global; ?>&nbsp;</td>
                <td><?php echo constant($global); ?>&nbsp;</td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>