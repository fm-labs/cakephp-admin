<?php $this->Breadcrumbs->add(__d('admin', 'Admin'), ['controller' => 'Admin', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Globals')); ?>
<div class="view container">
    <?= $this->Box->create(__d('admin', 'CAKEPHP Globals'), ['class' => 'box-solid']); ?>
    <table class="table table-striped table-hover">
        <?php foreach ($globals as $global) : ?>
            <tr>
                <td><?php echo $global; ?>&nbsp;</td>
                <td><?php echo constant($global); ?>&nbsp;</td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?= $this->Box->render(); ?>


    <?= $this->Box->create(__d('admin', 'PHP constants'), ['class' => 'box-solid']); ?>
    <table class="table table-striped table-hover">
        <?php foreach (get_defined_constants(false) as $global => $value) : ?>
            <tr>
                <td><?php echo $global; ?>&nbsp;</td>
                <td><?php echo $value; ?>&nbsp;</td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?= $this->Box->render(); ?>
</div>
