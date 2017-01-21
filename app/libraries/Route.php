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
 * Time: 03:34 AM
 */

namespace App\Lib\Utility;

class Route
{
    private $url;
    public function __construct($urlParam)
    {
        $this->url = $urlParam;
    }

    public function getUrl()
    {
        return $this->url;
    }
}
