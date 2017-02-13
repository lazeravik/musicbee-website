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
        ]
    ];

    return $route;
}