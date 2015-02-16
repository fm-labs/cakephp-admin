<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
?>
<div class="ui flash <?= h($class) ?>"><i class="close icon"></i><?= h($message) ?></div>
