<?php

namespace AdminLteTheme;

class AdminLteThemePlugin
{
    public function buildSettings()
    {
        return [
            'AdminLteTheme.skin_class' => [
                'type' => 'string',
                'input' => [
                    'type' => 'select',
                    'options' => [
                        'skin-blue'     => 'Blue',
                        'skin-yellow'   => 'Yellow',
                        'skin-red'      => 'Red',
                        'skin-purple'   => 'Purple',
                        'skin-black'    => 'Blue',
                        'skin-green'    => 'Green',
                    ],
                ],
                'default' => 'skin-blue',
            ],

            'AdminLteTheme.layout_class' => [
                'type' => 'string',
                'input' => [
                    'type' => 'select',
                    'empty' => true,
                    'options' => [
                        'fixed'             => 'Fixed',
                        'layout-boxed'      => 'Layout Boxed',
                        'layout-top-nav'    => 'Layout Top Nav',
                    ],
                ],
                'default' => null,
            ],

            'AdminLteTheme.sidebar_class' => [
                'type' => 'string',
                'input' => [
                    'type' => 'select',
                    'empty' => true,
                    'options' => [
                        'sidebar-mini' => 'Sidebar Mini',
                        'sidebar-mini sidebar-collapse' => 'Sidebar Mini Collapsed',
                    ],
                ],
                'default' => null,
            ],
        ];
    }
}
