<?php
/**
 * Copyright (c) 2017 AvikB, some rights reserved.
 *  Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 *  Spelling mistakes and fixes from community members.
 *
 */

/**
 * Created by PhpStorm.
 * User: Avik
 * Date: 19-01-2017
 * Time: 03:31 AM
 */

namespace App\Lib\Utility;

class Router
{
    protected $urlRoutes = [];

    public function addRoute(Route $newRoute)
    {
        $this->urlRoutes[] = $newRoute->getUrl();
    }

    public function route()
    {
        global $langList;
//        $urlParam = $_GET['param'];
        foreach ($this->urlRoutes as $url) {
            //echo
        }
    }

    public function getLanguageRoute()
    {
        if(isset($_GET['param'])){
            return explode("/", $_GET['param'])[0];
        }

        return null;
    }
}
