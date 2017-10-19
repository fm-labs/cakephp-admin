<div class="index">

    <?= $this->Html->link('Index', ['action' => 'index']); ?>

    <!-- Controller -->
    <section>
        <h3>Controller Actions</h3>
        <table class="table table-stripped">
            <tr>
                <th>Action</th>
                <th>Classname</th>
                <th>Loaded</th>
            </tr>
            <?php foreach ((array)$this->get('controller_actions') as $action => $actionClass): ?>
                <tr>
                    <td><?= h($action); ?></td>
                    <td><?= h($actionClass); ?></td>
                    <td><?= (array_key_exists($action, (array)$this->get('loaded_actions'))) ? "Yes" : "No"; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <!-- Actions -->
    <section>
        <h3>Loaded Actions</h3>
        <table class="table table-stripped">
            <tr>
                <th>Action</th>
                <th>Class</th>
                <th>Action Iface</th>
                <th>Index Iface</th>
                <th>Entity Iface</th>
                <th>Scope</th>
            </tr>
            <?php foreach ((array)$this->get('loaded_actions') as $action => $actionInfo): ?>
                <tr>
                    <td><?= h($action); ?></td>
                    <td><?= h($actionInfo['class']); ?></td>
                    <td><?= h($actionInfo['action_iface']); ?></td>
                    <td><?= h($actionInfo['index_iface']); ?></td>
                    <td><?= h($actionInfo['entity_iface']); ?></td>
                    <td><?= h(join(', ', (array) $actionInfo['scope'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <!-- Model -->
    <?php if ($this->get('model_class')): ?>
        <?php $schema = $this->get('model_schema'); ?>
        <section>
            <h3>Primary Model: <?= $this->get('model_class'); ?></h3>
            <table class="table table-stripped">
                <tr>
                    <th>Column Name</th>
                    <th>Type</th>
                    <th>Length</th>
                    <th>Debug</th>
                </tr>
                <?php foreach ($schema->columns() as $colName): ?>
                    <?php $column = $schema->column($colName); ?>
                    <tr>
                        <td><?= h($colName); ?></td>
                        <td><?= h($column['type']); ?></td>
                        <td><?= h($column['length']); ?></td>
                        <td><?= join(', ', array_map(function($k, $v) { return sprintf("%s: %s", (string)$k, (string)$v); }, array_keys($column), $column)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>
    <?php endif; ?>
</div>