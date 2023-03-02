<?php $this->Breadcrumbs->add(__d('admin', 'Admin'), ['controller' => 'Admin', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Logs'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(h($logFile)); ?>
<?php $this->Toolbar->addLink(
    __d('admin', 'List {0}', __d('admin', 'Logs')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('admin', 'Clear'),
    ['action' => 'clear', $logFile],
    ['data-icon' => 'trash outline']
) ?>
<?php $this->Toolbar->addLink(
    __d('admin', 'Delete'),
    ['action' => 'delete', $logFile],
    ['data-icon' => 'trash', 'confirm' => __d('admin', 'Are you sure?')]
) ?>
<div class="view admin logs">
    <h2 class="ui header">
        <?php echo h($logFile); ?>
    </h2>

    <div class="log-text">
        <textarea name="log" style="width:98%; height:90%; min-height: 500px;"><?=
            h($this->get('log'));
        ?></textarea>
        <div>
            <?= $this->Html->link(__d('admin', 'Load more'), ['action' => 'view', $logFile, 'page' => $page + 1], ['class' => 'btn btn-outline-secondary']); ?>
        </div>
    </div>
</div>