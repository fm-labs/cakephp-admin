<?php
return ['Backend.Layout.admin' => [
    'css' => [

    ],
    'scripts' => [

    ],
    'menus' => [
        'admin_primary',
        'admin_configure',
        'admin_util',
        'admin_footer',
    ],
    'blocks' => [
        'header_navbar_items' => [
            //'menu' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_menu', 'block' => 'header_navbar_menu'],
            'search' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_search', 'block' => 'header_navbar_items'],
            'messages' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_messages', 'block' => 'header_navbar_items'],
            'notifications' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_notifications', 'block' => 'header_navbar_items'],
            'tasks' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_tasks', 'block' => 'header_navbar_items'],
            'sysmenu' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_sysmenu', 'block' => 'header_navbar_items'],
            //'sysmenu' => ['cell' => 'Backend.SysMenu', 'block' => 'header_navbar_items'],
            'user' => ['element' => 'Backend.Layout/admin/header/navbar/navbar_user', 'block' => 'header_navbar_items'],
        ],
        'header' => [
            'header_navbar' => ['element' => 'Backend.Layout/admin/header'],
        ],
        'flash' => [
            ['element' => 'Backend.Layout/admin/flash'],
        ],
        //'toolbar' => [],
        //'breadcrumb' => [],
        'sidebar_items' => [
            //'menu' => ['cell' => 'Backend.SidebarMenu', 'block' => 'sidebar_items'],
            'menu' => ['element' => 'Backend.Layout/admin/sidebar/sidebar_menu', 'block' => 'sidebar_items'],
            //'search' => ['element' => 'Backend.Layout/admin/sidebar/sidebar_search', 'block' => 'sidebar_items'],
            //'user' => ['element' => 'Backend.Layout/admin/sidebar/sidebar_user', 'block' => 'sidebar_items'],
        ],
        'sidebar' => [
            'sidebar' => ['element' => 'Backend.Layout/admin/sidebar'],
        ],
        'top' => [
            ['element' => 'Backend.Layout/admin/content_header'],
        ],
        'left' => [],
        'content' => [],
        'right' => [],
        'bottom' => [],
        'footer' => [
            ['element' => 'Backend.Layout/admin/footer'],
        ],
        'control_sidebar' => [
            ['element' => 'Backend.Layout/admin/control_sidebar'],
        ],
    ],
]];
