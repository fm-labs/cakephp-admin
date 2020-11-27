<?php
return [
    'Settings' => [
        'Admin' => [
            'groups' => [
                'Admin.Dashboard' => [
                    'label' => __('Dashboard'),
                ],
                'Admin.Security' => [
                    'label' => __('Security'),
                ],
                'Admin.Theme' => [
                    'label' => __('Theme'),
                ],
            ],

            'schema' => [
                'Admin.Dashboard.title' => [
                    'group' => 'Admin.Dashboard',
                    'type' => 'string',
                    'input' => [],
                ],
                'Admin.Security.enabled' => [
                    'group' => 'Admin.Security',
                    'type' => 'boolean',
                    'label' => __('Enable Admin Security'),
                    'help' => __('Enables advanced security mechanism'),
                ],
                'Admin.Security.forceSSL' => [
                    'group' => 'Admin.Security',
                    'type' => 'boolean',
                    'label' => __('Force SSL'),
                    'help' => '',
                ],
                'Admin.Theme.name' => [
                    'group' => 'Admin.Theme',
                    'type' => 'string',
                    'input' => [
                        'type' => 'select',
                        'options' => [
                            'theme-default' => 'Default',
                        ],
                    ],
                    'default' => 'theme-default',
                ],
                'Admin.Theme.skin' => [
                    'group' => 'Admin.Theme',
                    'type' => 'string',
                    'input' => [
                        'type' => 'select',
                        'options' => [
                            'skin-blue' => __d('admin', 'Blue'),
                            'skin-yellow' => __d('admin', 'Yellow'),
                            'skin-red' => __d('admin', 'Red'),
                            'skin-purple' => __d('admin', 'Purple'),
                            'skin-black' => __d('admin', 'Blue'),
                            'skin-green' => __d('admin', 'Green'),
                        ],
                    ],
                    'default' => 'skin-blue',
                ],
                'Admin.Theme.bodyClass' => [
                    'group' => 'Admin.Theme',
                    'type' => 'string',
                ],
                'Admin.Theme.darkmode' => [
                    'group' => 'Admin.Theme',
                    'type' => 'boolean',
                ],
                'Admin.Theme.enableJsFlash' => [
                    'group' => 'Admin.Theme',
                    'label' => __('Pretty Flash messages'),
                    'type' => 'boolean',
                ],
                'Admin.Theme.enableJsAlerts' => [
                    'group' => 'Admin.Theme',
                    'label' => __('Pretty Alert messages'),
                    'type' => 'boolean',
                ],

                'Admin.CodeEditor.Ace.theme' => [
                    'group' => 'Admin.Theme',
                    'default' => 'twilight',
                    'input' => [
                        'type' => 'select',
                        'options' => [
                            'chaos' => 'chaos',
                            'chrome' => 'chrome',
                            'clouds' => 'clouds',
                            'cloud_midnight' => 'cloud_midnight',
                            'cobalt' => 'cobalt',
                            'crimson_editor' => 'crimson_editor',
                            'dawn' => 'dawn',
                            'dracula' => 'dracula',
                            'dreamweaver' => 'dreamweaver',
                            'eclipse' => 'eclipse',
                            'github' => 'github',
                            'gob' => 'gob',
                            'gruvbox' => 'gruvbox',
                            'idle_fingers' => 'idle_fingers',
                            'iplastic' => 'iplastic',
                            'katzenmilch' => 'katzenmilch',
                            'kr_theme' => 'kr_theme',
                            'kuroir' => 'kuroir',
                            'merbivore' => 'merbivore',
                            'merbivore_soft' => 'merbivore_soft',
                            'mono_industrial' => 'mono_industrial',
                            'monokai' => 'monokai',
                            'pastel_on_dark' => 'pastel_on_dark',
                            'solarized_dark' => 'solarized_dark',
                            'solarized_light' => 'solarized_light',
                            'sqlserver' => 'sqlserver',
                            'terminal' => 'terminal',
                            'textmate' => 'textmate',
                            'tomorrow' => 'tomorrow',
                            'tomorrow_night' => 'tomorrow_night',
                            'tomorrow_night_blue' => 'tomorrow_night_blue',
                            'tomorrow_night_bright' => 'tomorrow_night_bright',
                            'tomorrow_night_eighties' => 'tomorrow_night_eighties',
                            'twilight' => 'twilight',
                            'vibrant_ink' => 'vibrant_ink',
                            'xcode' => 'xcode',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
