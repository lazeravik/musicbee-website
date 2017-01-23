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
    private static $languageRoute;
    private static $locale;

    public static function init($languageRoute, $langList)
    {
       self::$langList = $langList;
       self::$languageRoute = $languageRoute;
    }

    /**
     * Get the language to load for the current page
     * @return string
     * @internal param $languageRoute
     * @internal param $langList
     */
    public static function getRequestedLanguage()
    {
        $langMatch =self::matchLanguage();
        if ($langMatch == "/") {
            if (isset($_COOKIE['language'])) {
                return $_COOKIE['language'];
            } else {
                return setting('default-lang');
            }
        } else {
            return $langMatch;
        }
    }

    /**
     * Set domain language and charset
     * @param $localeParam
     */
    public static function setLanguage($localeParam)
    {
        $encoding = 'UTF-8';
        T_setlocale(LC_MESSAGES, $localeParam);
        T_bindtextdomain($localeParam, path('locale-dir'));
        T_bind_textdomain_codeset($localeParam, $encoding);
        T_textdomain($localeParam);

        self::setLocale($localeParam);
        self::setLanguageCookie($localeParam);
    }

    /**
     * @param $locale
     * @return bool
     */
    public static function setLanguageCookie($locale)
    {
        return setcookie("language", $locale, time() + 60 * 60 * 24 * 30, '/');
    }

    /**
     * Get the matched language from language list for loading
     * @return string
     * @internal param $languageRoute
     */
    public static function matchLanguage()
    {
        $match = null;

        if (self::$languageRoute == null) {
            return "/";
        } elseif (strlen(self::$languageRoute) == 2) {
            $match =self::getFromLanguageArrayKey();
        } else {
            $match =self::getFromLanguageArrayItem();
        }

        if (self::$languageRoute != null && $match == null) {
            return "/";
        } else {
            return $match;
        }
    }

    /**
     * Get the language proper name if it exists in lang.lists.php file
     * @return null | string
     */
    public static function getFromLanguageArrayItem()
    {
        foreach (self::$langList as $lang) {
            if (strtolower(self::$languageRoute) == strtolower($lang[0])) {
                return $lang[0];
            }
        }

        return null;
    }


    /**
     * Get the proper language name if only the array key matches!
     * @return null | string
     */
    public static function getFromLanguageArrayKey()
    {
        if (array_key_exists(self::$languageRoute,self::$langList)) {
            return self::$langList[self::$languageRoute][0];
        }

        return null;
    }

    /**
     * Redirects user from a non localized url to a localized one!
     * @param Router $router
     * @internal param $localeParam
     */
    public static function redirectToUrlWithLanuageCode(Router $router)
    {
        self::$locale = self::getRequestedLanguage();

        self::setLanguage(self::$locale);

        if (self::matchLanguage() == "/" ||
            strtolower(self::getFromLanguageArrayItem()) == "") {
            $urltoRedirect = path('url') .
                $router->generateUrlWithLangParam(
                    self::$locale,
                    self::getFromLanguageArrayKey()
                );
            // 301 Moved Permanently to a localized url
            header('Location: '.$urltoRedirect, true, 301);
        }
    }

    /**
     * Set website locale
     * @param $locale
     */
    private static function setLocale($locale){
        self::$locale = $locale;
    }

    /**
     * Get website locale
     * @return string
     */
    public static function getLocale()
    {
        return self::$locale;
    }
}
