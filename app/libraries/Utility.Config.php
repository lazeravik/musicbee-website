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

    public static function getHttpUrlWithLangCode()
    {
        $langCode = !empty($_SESSION['language'])? $_SESSION['language']."/" : "";
        return self::getHttpUrl().strtolower($langCode);
    }

    public static function getRootDir()
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    public static function getStyleDir()
    {
        return self::getHttpUrl().'public/styles/';
    }

    public static function getScriptDir()
    {
        return self::getHttpUrl().'public/scripts/';
    }

    public static function getImageDir()
    {
        return self::getHttpUrl().'public/img/';
    }

    /**
     * Gets the current page URL
     *
     * @return string
     */
    public static function currentUrl()
    {
        $pageURL = self::isSecure()?"https://":"http://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }

        return $pageURL;
    }
}
