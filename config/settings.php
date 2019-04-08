<?php
return [
    'Settings' => [

        'backend_general' => [
            'label' => __('Backend General Settings'),
            'settings' => [
                'Backend.Dashboard.title' => [
                    'type' => 'string',
                    'input' => []
                ]
            ]
        ],

        'backend_security' => [
            'label' => __('Security'),
            'settings' => [
                'Backend.Security.enabled' => [
                    'type' => 'boolean',
                    'label' => 'Enable Backend Security',
                    'help' => 'Enables advanced security mechanism'
                ],
                'Backend.Security.forceSSL' => [
                    'type' => 'boolean',
                    'label' => 'Force SSL',
                    'help' => ''
                ]
            ]
        ],

        'backend_theme' => [
            'settings' => [
                'Backend.Theme.name' => [
                    'type' => 'string',
                    'input' => [
                        'type' => 'select',
                        'options' => [
                            'theme-default' => 'Default',
                        ]
                    ],
                    'default' => 'theme-default'
                ],
                'Backend.Theme.skin' => [
                    'type' => 'string',
                    'input' => [
                        'type' => 'select',
                        'options' => [
                            'skin-blue' => __d('backend', 'Blue'),
                            'skin-yellow' => __d('backend', 'Yellow'),
                            'skin-red' => __d('backend', 'Red'),
                            'skin-purple' => __d('backend', 'Purple'),
                            'skin-black' => __d('backend', 'Blue'),
                            'skin-green' => __d('backend', 'Green'),
                        ]
                    ],
                    'default' => 'skin-blue'
                ],
                'Backend.Theme.bodyClass' => [
                    'type' => 'string',
                ],
                'Backend.Theme.darkmode' => [
                    'type' => 'boolean',
                ],
                'Backend.Theme.enableJsFlash' => [
                    'label' => 'Pretty Flash messages',
                    'type' => 'boolean',
                ],
                'Backend.Theme.enableJsAlerts' => [
                    'label' => 'Pretty Alert messages',
                    'type' => 'boolean',
                ]
            ]
        ]
    ]
];
