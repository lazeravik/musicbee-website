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

    use App\Lib\TemplateManager as TM;
    use App\Lib\Utility\Template;
    use App\Lib\Utility\LanguageManager;

    //Template manager
    TM::setTemplate($template);

    $locale                 = LanguageManager::getLocale();
    $pageTitle              = TM::getData("title");
    $pageDescription        = TM::getData("description");
    $pageKeywords           = TM::getData("keywords");
    $styles                 = TM::getData("styles");
    $socialTags             = TM::getData("socialTags");
    $isFontHelperDisabled   = TM::getData("isFontHelperDisabled");

    $html = <<<HTML
<!DOCTYPE html>
<html>
    <head>
        <meta charset="{$setting['charset']}">
        <meta name="language" content="{$locale}">
HTML;

        /**
         * If the website is in test/development, then disable search engines from indexing it
         */
if ($setting['is_test']) :
    $html .=<<<HTML
    
        <meta name="robots" content="noindex">
HTML;
endif;

    $html .= <<<HTML

        <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
        <!-- responsive mobile deivicew support -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>{$pageTitle}</title>
        <meta name="description" content="{$pageDescription}">
        <meta name="keywords" content="musicbee, {$pageKeywords}">

        <link rel="alternate" href="{$link['url']}rss.xml"
              title="MusicBee updates" type="application/rss+xml"/>

        <link rel="help" href="{$link['faq']}"/>

        <link rel="apple-touch-icon" href="{$link['img-dir']}mb_icon_touch.png?{$setting['ver']}"/>
        <link rel="shortcut icon" href="{$link['favicon']}?{$setting['ver']}"/>

        <!-- Microsoft Tile support -->
        <meta content="" name="application-name"/>
        <meta content="#ffa13f" name="msapplication-TileColor"/>

        <meta property="og:locale" content="{$locale}"/>
        <meta property="og:type" content="website"/>
HTML;

/**
 * Social meta tags such as Facebook's open graph or twitter card
 */
    $html .= $socialTags;

/**
 * Load Stylesheets
 */
    $html .= <<<HTML
        <!-- Load external stylesheets and scripts -->
        <link rel="stylesheet" type="text/css" href="{$link['style-dir']}mb_main.css?{$setting['ver']}">
        <link rel="stylesheet" href="{$link['style-dir']}font-awesome-bower/font-awesome.css?4.7.0">

HTML;

/**
 * Get extra stylesheets
 */
    $html .= $styles;

/**
 * If font helper is not disables, then load it. This fixes cleartype font issues
 */

if (!$isFontHelperDisabled) :
    $html .= <<<HTML
    
        <script src="{$link['js-dir']}font.helper.js"></script>

HTML;
endif;

    $html .= <<<HTML
    </head>
<body>
HTML;


echo $html;
