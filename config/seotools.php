<?php

/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults' => [
            'title' => false, // set false to total remove
            'titleBefore' => false, // Put defaults.title before page title, like 'It's Over 9000! - Dashboard'
            'description' => false, // set false to total remove
            'separator' => ' - ',
            'keywords' => ['flutter framework', 'flutter', 'nylo', 'dart', 'app', 'development', 'framework'],
            'canonical' => null, // Set null for using Url::current(), set false to total remove
            'robots' => 'all', // Set to 'all', 'none' or any combination of index/noindex and follow/nofollow
        ],
        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google' => null,
            'bing' => null,
            'alexa' => null,
            'pinterest' => null,
            'yandex' => null,
            'norton' => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title' => false, // set false to total remove
            'description' => false, // set false to total remove
            'url' => false, // Set null for using Url::current(), set false to total remove
            'type' => false,
            'site_name' => false,
            'images' => [
                'https://nylo.dev/images/nylo_logo.png',
            ],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            'card' => 'summary',
            'site' => '@nylo_dev',
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title' => false, // set false to total remove
            'description' => false, // set false to total remove
            'url' => null, // Set null for using Url::current(), set false to total remove
            'type' => 'WebPage',
            'images' => [
                'https://nylo.dev/images/nylo_logo.png',
            ],
        ],
    ],
];
