<?php
return ['Admin.Layout.admin' => [
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
            //'menu' => ['element' => 'Admin.layout/admin/header/navbar/navbar_menu', 'block' => 'header_navbar_menu'],
            'search' => ['element' => 'Admin.layout/admin/header/navbar/navbar_search', 'block' => 'header_navbar_items'],
            'messages' => ['element' => 'Admin.layout/admin/header/navbar/navbar_messages', 'block' => 'header_navbar_items'],
            'notifications' => ['element' => 'Admin.layout/admin/header/navbar/navbar_notifications', 'block' => 'header_navbar_items'],
            'tasks' => ['element' => 'Admin.layout/admin/header/navbar/navbar_tasks', 'block' => 'header_navbar_items'],
            'sysmenu' => ['element' => 'Admin.layout/admin/header/navbar/navbar_sysmenu', 'block' => 'header_navbar_items'],
            //'sysmenu' => ['cell' => 'Admin.SysMenu', 'block' => 'header_navbar_items'],
            'user' => ['element' => 'Admin.layout/admin/header/navbar/navbar_user', 'block' => 'header_navbar_items'],
        ],
        'header' => [
            'header_navbar' => ['element' => 'Admin.layout/admin/header'],
        ],
        'flash' => [
            ['element' => 'Admin.layout/admin/flash'],
        ],
        //'toolbar' => [],
        //'breadcrumb' => [],
        'sidebar_items' => [
            //'menu' => ['cell' => 'Admin.SidebarMenu', 'block' => 'sidebar_items'],
            'menu' => ['element' => 'Admin.layout/admin/sidebar/sidebar_menu', 'block' => 'sidebar_items'],
            //'search' => ['element' => 'Admin.layout/admin/sidebar/sidebar_search', 'block' => 'sidebar_items'],
            //'user' => ['element' => 'Admin.layout/admin/sidebar/sidebar_user', 'block' => 'sidebar_items'],
        ],
        'sidebar' => [
            'sidebar' => ['element' => 'Admin.layout/admin/sidebar'],
        ],
        'top' => [
            ['element' => 'Admin.layout/admin/content_header'],
        ],
        'left' => [],
        'content' => [],
        'right' => [],
        'bottom' => [],
        'footer' => [
            ['element' => 'Admin.layout/admin/footer'],
        ],
        'control_sidebar' => [
            ['element' => 'Admin.layout/admin/control_sidebar'],
        ],
    ],
]];
