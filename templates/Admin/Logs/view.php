<?php $this->Breadcrumbs->add(__d('backend', 'Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend', 'Logs'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(h($logFile)); ?>
<?php $this->Toolbar->addLink(
    __d('backend', 'List {0}', __d('backend', 'Logs')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('backend', 'Clear'),
    ['action' => 'clear', $logFile],
    ['data-icon' => 'trash outline']
) ?>
<?php $this->Toolbar->addLink(
    __d('backend', 'Delete'),
    ['action' => 'delete', $logFile],
    ['data-icon' => 'trash', 'confirm' => __('Are you sure?')]
) ?>
<div class="view backend logs">
    <h2 class="ui header">
        <?php echo h($logFile); ?>
    </h2>

    <div class="log-text">
        <textarea name="log" style="width:98%; height:90%; min-height: 500px;"><?=
            h($this->get('log'));
        ?></textarea>
        <div>
            <?= $this->Html->link(__d('backend', 'Load more'), ['action' => 'view', $logFile, 'page' => $page + 1], ['class' => 'btn btn-default']); ?>
        </div>
    </div>
</div>