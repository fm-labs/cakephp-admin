<?php
/**
 * Backend generic flash message with bootstrap styles
 *
 * Parameters:
 *
 * - class: Alert style class
 * - dismiss: (optional) If set to FALSE, no close button will be shown (Close button is visible by default)
 * - title: Flash message title
 *
 */
$params += ['class' => null, 'dismiss' => true, 'title' => null];
?>
<div class="alert alert-dismissable alert-<?= h($params['class']) ?>" role="alert">
    <?php if (!isset($params['dismiss']) || $params['dismiss'] !== false): ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <?php endif; ?>
    <strong><?= (isset($params['title'])) ? h($params['title']) : "" ?></strong>
    <?= h($message) ?>
</div>
