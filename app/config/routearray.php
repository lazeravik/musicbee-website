<?php

function getRoutes() {
    $route = [
        [
            'url'           => '/',
            'controller'    => 'HomeController',
            'view'          => 'HomeView',
        ],
        [
            'url'           => '/downloads',
            'controller'    => 'DownloadController',
            'view'          => 'DownloadView',
        ],
    ];

    return $route;
}