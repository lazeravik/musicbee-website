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
 * Date: 17-01-2017
 * Time: 12:13 AM
 */

namespace App\Lib\Utility;

class Config
{
    public static function isSecure()
    {
        return
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || $_SERVER['SERVER_PORT'] == 443;
    }

    public static function getHttpUrl()
    {
        $httpStat = self::isSecure()?"https":"http";
        return $httpStat."://".$_SERVER['HTTP_HOST']."/";
    }

    public static function getRootDir()
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    public static function getStyleDir()
    {
        return self::getHttpUrl().'app/styles/';
    }

    public static function getScriptDir()
    {
        return self::getHttpUrl().'app/scripts/';
    }

    public static function getImageDir()
    {
        return self::getHttpUrl().'app/img/';
    }
}
