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


namespace App\Lib\Model;

use App\Lib\Help;
use App\Lib\Model;
use App\Lib\Settings;
use App\Lib\Utility\LanguageManager;

class HelpModel extends Model
{
    private $help = null;

    public function __construct()
    {
        $helpInstance = new Help();
        $this->help = $helpInstance->getHelp();
    }


    /**
     * Get help page content from WIKIA using their API
     * Content will be shown in localized version if defined here
     * @return null|string
     */
    public function getHelpPageContent()
    {
        /**
         * Since there is a russian translate of the wikia help page, we want to show the russian
         * FAQ when the site is requested in russian.
         */
        $wikiaLink = Settings::getLinks("wikiaLink") . '/index.php?action=render&title=';
        $defaultArticleTitle = "FAQ";
        $articleTitle = null;

        switch (LanguageManager::getLocale()) {
            case "ru_RU":
                $articleTitle = "FAQ_in_Russian";
                break;

            default:
                $articleTitle = $defaultArticleTitle;
                break;
        }

        $localizedWikiaLink = $wikiaLink;
        $localizedWikiaLink .= $articleTitle;

        $content = @file_get_contents($localizedWikiaLink);

        if ($content === false) {
            //Since localized wikia could not be loaded, try loading the default one
            //get the default API link
            $localizedWikiaLink = $wikiaLink;

            //add the default article title
            $localizedWikiaLink .= $defaultArticleTitle;
            $content = @file_get_contents($localizedWikiaLink);

            //if the default one failed too then show this error message
            if($content === false) {
                return "<p class='show_info warning'>" . lang("wikia_content_get_err") . "</p>";
            }
        }

        return $content;
    }

    /**
     * Get user help links for wikia as object
     * @return null | object
     */
    public function getSidebarUserHelp()
    {
        $user_help = @json_decode($this->help['help_links']['data'])->user_help;

        if ($user_help === false) {
            return null;
        } else {
            return $user_help;
        }
    }

    /**
     * Get developer help links for wikia as object
     * @return null | object
     */
    public function getSidebarDevHelp()
    {
        $user_help = @json_decode($this->help['help_links']['data'])->dashboard;

        if ($user_help === false) {
            return null;
        } else {
            return $user_help;
        }
    }


    public function getWikiaPopularPosts()
    {
        $content = @file_get_contents(Settings::getLinks("wikiaLink")."/api/v1/Articles/Popular?limit=10");

        if($content !== false){
            return $this->fixWikiaUrl($content);
        }

        return null;
    }

    public function getWikiaMostViewedPosts()
    {
        $content = @file_get_contents(Settings::getLinks("wikiaLink")."/api/v1/Articles/Top?limit=10");

        if($content !== false){
            return $this->fixWikiaUrl($content);
        }

        return null;
    }

    public function getWikiaNewPosts()
    {
        $content = @file_get_contents(Settings::getLinks("wikiaLink")."/api/v1/Articles/New?limit=20&minArticleQuality=10");

        if($content !== false){
            return $this->fixWikiaUrl($content);
        }

        return null;
    }

    /**
     * Adds host to wikia url
     * @param $content
     * @return mixed
     */
    public function fixWikiaUrl($content)
    {
        $content = @json_decode($content);

        if($content !== false) {
            $items = $content->items;
            foreach ($items as $key => $item) {
                $items[$key]->url = $content->basepath . $item->url;
            }

            return $items;
        }

        return null;
    }

    public function getTopHelpPalette()
    {
        $top_help = @json_decode($this->help['help_links']['data'])->top_help_popup;

        if ($top_help !== false) {
            return $top_help;
        }

        return null;
    }
}