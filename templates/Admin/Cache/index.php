<?php
/**
 * @var array $caches Map of cache configurations
 */
$this->assign('heading', __('Cache configuration'));
?>
<div class="index">
    <table class="table table-condensed">
        <thead>
        <tr>
            <th><?= __d('admin', 'Alias'); ?></th>
            <th><?= __d('admin', 'Cache config'); ?></th>
            <th class="actions"><?= __d('admin', 'Actions'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($caches as $alias => $cacheConfig) : ?>
            <tr>
                <td><?= h($alias); ?></td>
                <td><?= json_encode($cacheConfig, JSON_PRETTY_PRINT); ?></td>
                <td class="actions">
                    <?= $this->Html->link(__d('admin', 'Clear cache'), ['action' => 'clear', $alias], ['class' => 'btn btn-sm btn-default']); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>