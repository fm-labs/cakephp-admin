<?php
echo $this->element('Admin.layout/admin/sidebar/sidebar_collapsible_menu_item', ['menu' => $primary ?? []]);
echo $this->element('Admin.layout/admin/sidebar/sidebar_collapsible_menu_item', ['menu' => $secondary ?? []]);

$systemMenu = [[
    'title' => 'System',
    'url' => false,
    'children' => $system ?? []
]];
echo $this->element('Admin.layout/admin/sidebar/sidebar_collapsible_menu_item', ['menu' => $systemMenu]);


$developerMenu = [[
    'title' => 'Developer',
    'url' => false,
    'children' => $developer ?? []
]];
echo $this->element('Admin.layout/admin/sidebar/sidebar_collapsible_menu_item', ['menu' => $developerMenu]);

