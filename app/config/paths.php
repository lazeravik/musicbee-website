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

use App\Lib\Utility\Config as cfg;

function path($pathname = null)
{
    //All links defined here. MODIFY IT WHEN FOLDER/SITE STRUCTURE CHANGES!
    $link                         = array();
    $link['root']                 = cfg::getRootDir().'/app/';
    $link['url-nolang']           = cfg::getHttpUrl();
    $link['url']                  = cfg::getHttpUrlWithLangCode();
    $link['app-url']              = $link['url'].'app/';
    $link['favicon']              = $link['url']."app/favicon.ico";
    $link['download']             = $link['url'].'downloads/';
    $link['rss']                  = $link['url'].'rss/';
    $link['home']                 = $link['url'];
    $link['forum']                = $link['url-nolang'].'forum/';
    $link['admin']['forum-panel'] = $link['forum'].'?action=admin';
    $link['login']                = $link['forum'].'?action=login';
    $link['register']             = $link['forum'].'?action=register';
    $link['support']              = $link['url'].'support/';
    $link['addon']['home']        = $link['url'].'addons/';
    $link['addon']['dashboard']   = $link['url'].'dashboard/';
    $link['help']                 = $link['url'].'help/';
    $link['release-note']         = $link['url'].'release-note/';
    $link['press']                = $link['url'].'press/';
    $link['api']                  = $link['url'].'api/';
    $link['bugreport']            = $link['url'].'bug/';
    $link['redirect']             = $link['url'].'out/';
    $link['kb']                   = $link['url'].'kb/';
    $link['credit']               = $link['url'].'credit/';
    $link['logout']               = $link['url'].'logout/';
    $link['incl-dir']             = $link['root'].'includes/';
    $link['template-dir']         = $link['root']. 'view/templates/';
    $link['locale-dir']           = $link['root']. 'locale/';
    $link['model-dir']            = $link['root']. 'model/';
    $link['view-dir']             = $link['root']. 'view/';
    $link['controller-dir']       = $link['root']. 'controllers/';

    //public directory
    $link['style-dir']            = cfg::getStyleDir();
    $link['img-dir']              = cfg::getImageDir();
    $link['js-dir']               = cfg::getScriptDir();


    if ($pathname != null) {
        if (!empty($link[$pathname])) {
            return $link[$pathname];
        }

        return null;
    }

    return $link;
}
