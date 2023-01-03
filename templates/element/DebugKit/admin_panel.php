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
        <?= $this->Html->link(__('Admin Dashboard'), ['_name' => 'admin:system:dashboard']); ?> |
        <?= $this->Html->link(__('Logout'), ['_name' => 'admin:system:user:logout']); ?> |
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
