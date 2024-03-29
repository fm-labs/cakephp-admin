<?php
/**
 * DebugKit - Admin Panel
 *
 * @var \Admin\Core\AdminPluginCollection|null $plugins
 */
?>
<div class="debugkit-panel">
    <h2>Admin Panel</h2>
    <p>
        Quicklinks:
        <?= $this->Html->link(__d('admin', 'Admin Dashboard'), ['_name' => 'admin:index']); ?> |
        <?= $this->Html->link(__d('admin', 'Logout'), ['_name' => 'admin:auth:user:logout']); ?> |
    </p>

    <h3>Locale</h3>
    <?php echo $this->element('Admin.array_to_tablelist', ['data' => $this->get('locale')]); ?>

    <h3>Config</h3>
    <?php echo $this->element('Admin.array_to_tablelist', ['data' => $this->get('config')]); ?>

    <h3>Plugins</h3>
    <table class="table table-striped">
        <tr>
            <th>Plugin name</th>
        </tr>
        <?php
        if ($plugins) {
            foreach ($plugins as $pluginName => $plugin) {
                echo sprintf(
                    '<tr><td>%s</td></tr>',
                    $pluginName
                );
            }
        }
        ?>
    </table>
</div>
