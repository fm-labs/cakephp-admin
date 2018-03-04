<?php
use Cake\Cache\Cache;
?>
<div class="index">
    <table class="table table-compact">
        <tr>
            <th><?= __d('backend','Cache config'); ?></th>
            <th class="actions"><?= __d('backend','Actions'); ?></th>
        </tr>
        <?php foreach(Cache::configured() as $key): ?>
        <tr>
            <td><?= h($key); ?></td>
            <td class="actions">
                <?= $this->Html->link(__d('backend','Clear cache'), ['action' => 'clear', $key], ['class' => 'btn btn-sm btn-default']); ?>
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