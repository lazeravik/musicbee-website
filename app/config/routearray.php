<?php

/**
 * Route defination example:
 *      [
 *          'url'           => '/',
 *          'view'          => 'view-php-file-name',
 *          'model'         => 'model-php-file-name',
 *      ],
 *
 * @return array
 */
function getRoutes() {
    $route = [
        [
            'url'           => '/',
            'view'          => 'HomeView',
            'model'         => 'HomeModel',
        ],
        [
            'url'           => '/downloads',
            'view'          => 'DownloadView',
        ],
        [
            'url'           => '/help',
            'view'          => 'HelpView',
            'model'         => 'HelpModel',
        ],
        [
            'url'           => '/credit',
            'view'          => 'CreditView',
        ],
        [
            'url'           => '/api',
            'view'          => 'ApiView',
            'model'         => 'ApiModel',
        ],
        [
            'url'           => '/release-note',
            'view'          => 'ReleasenoteView',
            'model'         => 'ReleasenoteModel',
        ]
    ];

    return $route;
}