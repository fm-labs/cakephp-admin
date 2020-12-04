<?php
/**
 * DebugKit - Admin Panel
 *
 * @var \Admin\Core\AdminPluginCollection|null $plugins
 */
?>
<div class="debugkit-panel">
    <h2>Admin Panel</h2>

    <table class="table table-striped">
        <tr>
            <th>Admin Plugin name</th>
        </tr>
        <?php
        if ($plugins) {
            foreach ($plugins as $pluginName => $plugin) {
                echo sprintf('<tr><td>%s</td></tr>', $pluginName);
            }
        }
        ?>
    </table>
</div>
