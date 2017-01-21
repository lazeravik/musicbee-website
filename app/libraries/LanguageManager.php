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

class LanguageManager
{
    private static $langList;

    /**
     * Get the language to load for the current page
     * @param $languageRoute
     * @param $langList
     * @return string
     */
    public static function getRequestedLanguage($languageRoute, $langList)
    {
        global $setting;

        self::$langList = $langList;
        $langMatch = self::matchLanguage($languageRoute);

        if($langMatch == "/"){
            if(isset($_COOKIE['language'])){
                return $_COOKIE['language'];
            } else {
                return $setting['default-lang'];
            }
        } else {
            return $langMatch;
        }
    }

    /**
     * Set domain language and charset
     * @param $locale
     */
    public static function setLanguage($locale)
    {
        global $link;
        $encoding = 'UTF-8';
        T_setlocale(LC_MESSAGES, $locale);
        T_bindtextdomain($locale, $link['locale-dir']);
        T_bind_textdomain_codeset($locale, $encoding);
        T_textdomain($locale);

        self::setLanguageCookie($locale);
    }

    /**
     * @param $locale
     * @return bool
     */
    public static function setLanguageCookie($locale)
    {
        //$_COOKIE['language'] = $locale;
        return setcookie("language", $locale, time() + 60 * 60 * 24 * 30, '/');
    }

    /**
     * Get the matched language from language list for loading
     * @param $languageRoute
     * @return string
     */
    public static function matchLanguage($languageRoute)
    {
        $match = null;
        if ($languageRoute == null) {
            return "/";
        } elseif (strlen($languageRoute) == 2) {
            if (array_key_exists($languageRoute, self::$langList)) {
                $match = self::$langList[$languageRoute][0];
            }
        } else {
            foreach (self::$langList as $lang) {
                if (strtolower($languageRoute) == strtolower($lang[0])) {
                    $match = $lang[0];
                }
            }
        }

        if ($languageRoute != null && $match == null) {
            return "/";
        } else {
            return $match;
        }
    }

}
