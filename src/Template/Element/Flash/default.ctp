<?php
#$class = 'message';
#if (!empty($params['class'])) {
#    $class .= ' ' . $params['class'];
#}
$map = [
    'error' => 'danger'
];
if (array_key_exists($params['class'], $map)) {
    $params['class'] = $map[$params['class']];
}
?>
<div class="alert alert-dismissable alert-<?= h($params['class']) ?>" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <?= h($message) ?>
</div>
