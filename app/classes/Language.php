<?php
/**
 * Copyright (c) 2016 AvikB, some rights reserved.
 *  Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 *  Spelling mistakes and fixes from community members.
 *
 */

namespace App\Lib;

class Language
{
    public function __construct()
    {
        global $language, $lang_filelist, $lang;

        //List of all indexed language files
        require_once $_SERVER['DOCUMENT_ROOT'].'/app/includes/languages/lang.list.php';

        //Default language file. DO NOT REMOVE IT!
        require_once $_SERVER['DOCUMENT_ROOT'].'/app/includes/languages/en-us.php';

        //Default language is english
        $language = $this->setLanguage();

        if (!empty($language)) {
            if (file_exists($_SERVER['DOCUMENT_ROOT'].'/app/includes/languages/'.$language['filename'])
                && $language['meta'] != 'en-us') {
                include_once $_SERVER['DOCUMENT_ROOT'].'/app/includes/languages/'.$language['filename'];
            }
        }
    }

    /**
     * Set language cookie and returns language file details
     *
     * @return mixed
     */
    public function setLanguage()
    {
        if (isset($_GET['lang'])) {
            $language = $this->getLanguageFileName($_GET['lang']);
        } elseif (isset($_COOKIE['lang'])) {
            $language = $this->getLanguageFileName($_COOKIE['lang']);
        } else {
            $language = $this->getLanguageFileName('en-us');
        }

     //Sets the language cookie for 30 days
        setcookie('lang', $language['meta'], time() + 60 * 60 * 24 * 30, '/');

        return $language;
    }


    /**
     * gets language file list & details from lang.list.php
     *
     * @param $lang
     *
     * @return mixed
     */
    private function getLanguageFileName($lang)
    {
        global $lang_filelist;

        if (array_key_exists($lang, $lang_filelist)) {
            return $lang_filelist[$lang];
        }
    }
}
