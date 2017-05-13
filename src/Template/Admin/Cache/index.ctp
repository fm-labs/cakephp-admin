<?php
use Cake\Cache\Cache;
?>
<div class="index">
    <table class="table table-compact">
        <tr>
            <th><?= __('Cache config'); ?></th>
            <th class="actions"><?= __('Actions'); ?></th>
        </tr>
        <?php foreach(Cache::configured() as $key): ?>
        <tr>
            <td><?= h($key); ?></td>
            <td class="actions">
                <?= $this->Html->link(__('Clear cache'), ['action' => 'clear', $key], ['class' => 'btn btn-sm btn-default']); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php
    foreach(Cache::configured() as $key) {

        $cache = Cache::config($key);
        if (is_object($cache['className'])) {
            if ($cache['className'] instanceof \DebugKit\Cache\Engine\DebugEngine && !$cache['className']->engine()) {
                $cache['className']->init();
            }
            debug($cache['className']->config());
        }
    }
    ?>
</div>