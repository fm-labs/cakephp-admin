<?php
/**
 * @var \Cake\View\View $this
 * @var array $pluginInfo
 */
?>
<div class="view">
    <h2><?= __('Plugin {0}', $pluginInfo['name']); ?></h2>

    <?= $this->Html->link(__('Back to {0}', __('Plugins')), ['action' => 'index']); ?>
</div>