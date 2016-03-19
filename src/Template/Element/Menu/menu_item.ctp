<?php
/**
 * Menu/menu_item Element
 *
 * Renders a simple menu item.
 */
$item = (isset($item)) ? $item : null;
$level = (isset($level)) ? $level : 0;

if (!$item) {
    debug(sprintf("No menu item given"));
    return;
}

$item['title'] = (isset($item['title'])) ? $item['title'] : __('Untitled Menu Item');
$item['url'] = (isset($item['url'])) ? $item['url'] : false;
$item['attr'] = (isset($item['attr'])) ? $item['attr'] : [];

?>
<?php
/**
 * Item with children
 */
if (isset($item['_children']) && !empty($item['_children'])): ?>
    <div class="ui dropdown item <?= sprintf('level-%s', $level) ?>">
        <i class="dropdown icon"></i>
        <?=
        $this->Ui->link(
            $item['title'],
            $item['url'],
            $item['attr']
        ); ?>
        <div class="menu">
            <?php foreach ($item['_children'] as $child): ?>
                <?= $this->element('Backend.Menu/menu_item', ['item' => $child, 'level' => $level + 1]); ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php
/**
 * Item without children
 */
else:
?>
    <?php
        $item['attr'] = $this->Html->addClass((array) $item['attr'], 'item');
        echo $this->Ui->link(
            $item['title'],
            $item['url'],
            $item['attr']
        ); ?>
<?php
endif;
