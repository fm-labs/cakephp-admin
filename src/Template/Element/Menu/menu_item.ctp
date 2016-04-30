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
    <li role="presentation" class="dropdown <?= sprintf('level-%s', $level) ?>">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            Dropdown <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php foreach ($item['_children'] as $child): ?>
                <?= $this->element('Backend.Menu/menu_item', ['item' => $child, 'level' => $level + 1]); ?>
            <?php endforeach; ?>
        </ul>
    </li>
<?php
/**
 * Item without children
 */
else:
?>
    <li class="" role="presentation">
    <?php
        $item['attr'] = $this->Html->addClass((array) $item['attr'], 'item');
        echo $this->Ui->link(
            $item['title'],
            $item['url'],
            $item['attr']
        ); ?>
    </li>
<?php
endif;
