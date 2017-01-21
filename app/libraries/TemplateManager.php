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

namespace App\Lib;


class TemplateManager
{
    public static function getTitle()
    {
        global $title;
        return $title;
    }

    public static function getDescription()
    {
        global $description;
        return $description;
    }

    public static function getKeywords()
    {
        global $keywords;
        return $keywords;
    }

    public static function getSocialMetaTags()
    {
        global $socialMetaTags;

        if(!empty($socialMetaTags)) {
            return $socialMetaTags;
        }
        return "";
    }

    public static function isFontHelperDisabled()
    {
        global $isFontHelperDisabled;

        if(!empty($isFontHelperDisabled)) {
            return (bool)$isFontHelperDisabled;
        }
    }

    public static function getStyleSheets()
    {
        global $styleSheets;

        if(!empty($styleSheets)){
            return $styleSheets;
        }
        return "";
    }
}