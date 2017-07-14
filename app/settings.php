<?php
return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,

        // View settings
        'view' => [
            'template_path' => __DIR__ . '/templates',
            'twig' => [
                // @todo Set this to something more sensible
                'cache' => sys_get_temp_dir(),
                'debug' => true,
                'auto_reload' => true,
            ],
        ],

        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../log/app.log',
        ],

        // Neato Botvac settings
        'neato' => [
            'email' => '', // Redacted
        	'password' => '', // Redacted
            'serial' => '', // Redacted
            'secret' => '', // Redacted
        ],
    ],
];
