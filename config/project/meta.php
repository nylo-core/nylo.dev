<?php

return [
    'repos' => [
        'nylo' => [
            'version' => 'v4.3.1',
            'repo_name' => 'http://github.com/nylo-core/nylo'
        ],

        'framework' => [
            'version' => 'v4.1.4',
            'repo_name' => 'http://github.com/nylo-core/framework'
        ],

        'support' => [
            'version' => 'v4.3.1',
            'repo_name' => 'http://github.com/nylo-core/support'
        ],
    ],

    'ga_id' => env('GOOGLE_ANALYTICS_ID'),
    
    'fa_integrity' => env('FONT_AWESOME_INTEGRITY'),
];
