<?php

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
            'model'         => 'DownloadModel',
        ],
    ];

    return $route;
}