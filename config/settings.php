<?php
return [
    'Settings' => [

        'Admin.General' => [
            'label' => __('Admin General Settings'),
            'settings' => [
                'Admin.Dashboard.title' => [
                    'type' => 'string',
                    'input' => [],
                ],
            ],
        ],

        'Admin.Security' => [
            'label' => __('Security'),
            'settings' => [
                'Admin.Security.enabled' => [
                    'type' => 'boolean',
                    'label' => 'Enable Admin Security',
                    'help' => 'Enables advanced security mechanism',
                ],
                'Admin.Security.forceSSL' => [
                    'type' => 'boolean',
                    'label' => 'Force SSL',
                    'help' => '',
                ],
            ],
        ],

        'Admin.Theme' => [
            'settings' => [
                'Admin.Theme.name' => [
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
                    'type' => 'string',
                ],
                'Admin.Theme.darkmode' => [
                    'type' => 'boolean',
                ],
                'Admin.Theme.enableJsFlash' => [
                    'label' => 'Pretty Flash messages',
                    'type' => 'boolean',
                ],
                'Admin.Theme.enableJsAlerts' => [
                    'label' => 'Pretty Alert messages',
                    'type' => 'boolean',
                ],
            ],
        ],
    ],
];
