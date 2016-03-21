<?php
/**
 * Menu Element
 *
 * Renders a simple menu structure.
 */

$menu       = isset($menu) ? $menu : false;
$class      = isset($class) ? $class : '';
$id         = isset($id) ? $id : null;
$wrapper    = isset($wrapper) ? $wrapper : 'ul';
//$levels     = isset($levels) ? $levels : -1;

debug($class);
debug($wrapper);

if (!$menu) {
    debug("No menu set");
    return false;
}

$html = "";
foreach ($menu as $menuItem) {
    $html .= $this->element('Backend.Menu/menu_item', ['item' => $menuItem]);
}
echo $this->Html->tag($wrapper, $html, ['id' => $id, 'class' => $class]);

