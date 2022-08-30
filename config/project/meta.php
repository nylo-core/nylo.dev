<?php

return [
    'repos' => [
        'nylo' => [
            'version' => 'v3.2.1',
            'repo_name' => 'http://github.com/nylo-core/nylo'
        ],

        'framework' => [
            'version' => 'v3.3.0',
            'repo_name' => 'http://github.com/nylo-core/framework'
        ],

        'support' => [
            'version' => 'v3.4.0',
            'repo_name' => 'http://github.com/nylo-core/support'
        ],
    ],

    'ga_id' => env('GOOGLE_ANALYTICS_ID'),
    
    'fa_integrity' => env('FONT_AWESOME_INTEGRITY'),

    'docs' => [
        '1.x' => [
            'what-is-nylo',
            'requirements',
            'installation',
            'configuration',
            'directory-structure',
            'router',
            'controllers',
            'metro',
            'app-icons',
            'localization',
            'storage',
            'themes',
            'assets',
        ],
        '2.x' => [
            'what-is-nylo',
            'requirements',
            'upgrade-guide',
            'installation',
            'configuration',
            'directory-structure',
            'router',
            'controllers',
            'metro',
            'app-icons',
            'storage',
            'localization',
            'validation',
            'themes',
            'assets',
        ],
        '3.x' => [
            'what-is-nylo',
            'requirements',
            'upgrade-guide',
            'installation',
            'configuration',
            'directory-structure',
            'router',
            'controllers',
            'metro',
            'app-icons',
            'storage',
            'localization',
            'validation',
            'themes',
            'assets',
            'networking',
            'providers',
            'decoders',
            'events',
        ]
    ]
];
