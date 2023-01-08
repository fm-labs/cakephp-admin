<?php
if (!isset($array)) {
    echo "No array given";
    return;
}
$class = (isset($class)) ? $class : 'array-list';
$class = (isset($collapse)) ? $class .= ' collapse' : $class;
?>
<ul class="<?= $class; ?>">
    <?php foreach($array as $k => $v): ?>
        <?php $itemClass = (is_array($v)) ? 'has-children' : ''; ?>
        <li class="<?= $itemClass; ?>">
            <span class="key"><?= h($k); ?></span>
            <?php if (is_array($v)) {
                echo $this->element('Sugar.array_to_list', ['array' => $v, 'collapse' => true]);
            } else {
                echo '<span class="value">' . h($v) . '</span>';
            }
            ?>
        </li>
    <?php endforeach; ?>
</ul>
