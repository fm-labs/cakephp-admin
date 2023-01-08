<?php
/**
 * ! UNUSED !
 * ! EXPERIMENTAL !
 */
return [
    'Admin.Theme' => [
        'layout_blocks' => [
            'header' => [

            ],
            'content' => [

            ],
            'footer' => [

            ]
        ],
        'menus' => [
            'sidebar_menu' => [],
            'top_menu' => [],
            'footer_menu' => [],
            'mobile_menu' => [],
        ],
        'helpers' => [
            'Admin.SomeAdminHelper' => ['autoload' => true]
        ],
        'dashboard' => [
            'Theme.AwesomeDashboardElement' => ['enable' => true]
        ]
    ],
    'Admin.Ui.layout.admin' => [
        'header' => ['className' => \Admin\Ui\Layout\Header::class],
        'footer' => ['className' => \Admin\Ui\Layout\Footer::class],
        'sidebar' => ['className' => \Admin\Ui\Layout\Sidebar::class],
        //'header' => ['\Admin\Ui\Layout\Header' => []],
        //'header/nav' => ['\Admin\Ui\Layout\Header\NavLeft', '\Admin\Ui\Layout\Header\NavRight'],
        //'header/nav/left/panels.0' => '\Admin\Ui\Layout\Header\Panel\MenuPanel',
        //'header/nav/right/panels.0' => '\Admin\Ui\Layout\Header\Panel\MenuPanel',
        //'header/nav/right/panels.1' => '\Admin\Ui\Layout\Header\Panel\UserPanel',
        //'header/nav/right/panels.2' => '\Admin\Ui\Layout\Header\Panel\MessagesPanel',
        //'header/nav/right/panels.3' => '\Admin\Ui\Layout\Header\Panel\TasksPanel',
        //'footer' => [ '\Admin\Ui\Layout\Footer' => ['foo' => 'bar', 'elementName' => ''] ],
        //'footer/nav' => '\Admin\Ui\Layout\Footer\Nav',
        //'sidebar' => '\Admin\Ui\Layout\Sidebar',
        //'sidebar/panels.0' => '\Admin\Ui\Layout\Sidebar\MenuPanel',
        //'sidebar/panels.1' => '\Admin\Ui\Layout\Sidebar\SearchPanel',
        //'sidebar/panels.\Admin\Ui\Layout\Sidebar\UserPanel' => false, // disabled
        //'sidebar/panels.\Admin\Ui\Layout\Sidebar\MenuPanel' => ['menuId' => 'sidebar_footer'],
        //'some/section' => '\Some\Class',
    ],
    'Admin.Ui.dashboard.default' => [
        'panels' => [
            '\Admin\Ui\Dashboard\Panel\UserPanel',
            '\Admin\Ui\Dashboard\Panel\SystemPanel',
        ],
    ],
];
