<?php

function getRoutes() {
    $route = [
        [
            'url'           => '/',
            'controller'    => 'HomeController',
            'view'          => 'HomeView',
            'model'         => 'HomeModel',
        ],
        [
            'url'           => '/downloads',
            'controller'    => 'DownloadController',
            'view'          => 'DownloadView',
            'model'         => 'DownloadModel',
        ],
    ];

    return $route;
}