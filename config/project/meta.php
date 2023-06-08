<?php

return [
    'repos' => [
        'nylo' => [
            'version' => 'v5.0.5',
            'repo_name' => 'http://github.com/nylo-core/nylo'
        ],

        'framework' => [
            'version' => 'v5.0.4',
            'repo_name' => 'http://github.com/nylo-core/framework'
        ],

        'support' => [
            'version' => 'v5.1.3',
            'repo_name' => 'http://github.com/nylo-core/support'
        ],
    ],

    'ga_id' => env('GOOGLE_ANALYTICS_ID'),
    
    'fa_integrity' => env('FONT_AWESOME_INTEGRITY'),
];
