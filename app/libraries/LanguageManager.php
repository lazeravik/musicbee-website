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
    private $langList;
    private $languageRoute;


    public function init($languageRoute, $langList)
    {
        $this->langList = $langList;
        $this->languageRoute = $languageRoute;
    }

    /**
     * Get the language to load for the current page
     * @return string
     * @internal param $languageRoute
     * @internal param $langList
     */
    public function getRequestedLanguage()
    {
        global $setting;

        $langMatch = $this->matchLanguage();
        if ($langMatch == "/") {
            if (isset($_COOKIE['language'])) {
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
    public function setLanguage($locale)
    {
        global $link;
        $encoding = 'UTF-8';
        T_setlocale(LC_MESSAGES, $locale);
        T_bindtextdomain($locale, $link['locale-dir']);
        T_bind_textdomain_codeset($locale, $encoding);
        T_textdomain($locale);

        $this->setLanguageCookie($locale);
    }

    /**
     * @param $locale
     * @return bool
     */
    public function setLanguageCookie($locale)
    {
        return setcookie("language", $locale, time() + 60 * 60 * 24 * 30, '/');
    }

    /**
     * Get the matched language from language list for loading
     * @return string
     * @internal param $languageRoute
     */
    public function matchLanguage()
    {
        $match = null;

        if ($this->languageRoute == null) {
            return "/";
        } elseif (strlen($this->languageRoute) == 2) {
            $match = $this->getFromLanguageArrayKey();
        } else {
            $match = $this->getFromLanguageArrayItem();
        }

        if ($this->languageRoute != null && $match == null) {
            return "/";
        } else {
            return $match;
        }
    }

    /**
     * Get the language proper name if it exists in lang.lists.php file
     * @return null | string
     */
    public function getFromLanguageArrayItem()
    {
        foreach ($this->langList as $lang) {
            if (strtolower($this->languageRoute) == strtolower($lang[0])) {
                return $lang[0];
            }
        }

        return null;
    }


    /**
     * Get the proper language name if only the array key matches!
     * @return null | string
     */
    public function getFromLanguageArrayKey()
    {
        if (array_key_exists($this->languageRoute, $this->langList)) {
            return $this->langList[$this->languageRoute][0];
        }

        return null;
    }
}
