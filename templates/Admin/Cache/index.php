<?php
/**
 * @var array $caches Map of cache configurations
 */
$this->assign('heading', __d('admin', 'Cache configuration'));

$formatter = function ($url) {
    $parts = [];
    krsort($url);
    foreach ($url as $key => $val) {
        if (is_array($val)) {
            $val = json_encode($val);
        }
        $parts[] = sprintf("<strong>%s:</strong> %s", $key, $val);
    }
    return join("<br>", $parts);
};
?>
<div class="index">
    <table class="table table-sm">
        <tr>
            <th><?= __d('admin', 'Alias'); ?></th>
            <th><?= __d('admin', 'Cache config'); ?></th>
            <th class="actions"><?= __d('admin', 'Actions'); ?></th>
        </tr>
        <tbody>
        <?php foreach ($caches as $alias => $cacheConfig) : ?>
            <tr>
                <td><?= h($alias); ?></td>
                <td><?= $formatter($cacheConfig); ?></td>
                <td class="actions">
                    <?= $this->Html->link(
                            __d('admin', 'Clear cache'),
                            ['action' => 'clear', $alias],
                            ['class' => 'btn btn-sm btn-outline-primary']); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>