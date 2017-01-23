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

function path($pathname = null) {
    //All links defined here. MODIFY IT WHEN FOLDER/SITE STRUCTURE CHANGES!
    $link                         = array();
    $link['root']                 = cfg::getRootDir().'/app/';
    $link['url']                  = cfg::getHttpUrl();
    $link['app-url']              = $link['url'].'app/';
    $link['favicon']              = $link['url']."app/favicon.ico";
    $link['download']             = $link['url'].'downloads/';
    $link['rss']                  = $link['url'].'rss/';
    $link['home']                 = $link['url'];
    $link['forum']                = $link['url'].'forum/';
    $link['admin']['forum-panel'] = $link['forum'].'?action=admin';
    $link['login']                = $link['forum'].'?action=login';
    $link['register']             = $link['forum'].'?action=register';
    $link['support']              = $link['url'].'support/';
    $link['addon']['home']        = $link['url'].'addons/';
    $link['addon']['dashboard']   = $link['url'].'dashboard/';
    $link['help']                 = $link['url'].'help/';
    $link['faq']                  = $link['help'].'faq/';
    $link['release-note']         = $link['help'].'release-note/';
    $link['press']                = $link['help'].'press/';
    $link['api']                  = $link['help'].'api/';
    $link['bugreport']            = $link['url'].'bug/';
    $link['redirect']             = $link['url'].'out/';
    $link['404']                  = $link['root']."pages/error/404.php";
    $link['kb']                   = $link['url'].'kb/';
    $link['credit']               = $link['help'].'credit/';
    $link['logout']               = $link['url'].'logout/';
    $link['incl-dir']             = $link['root'].'includes/';
    $link['template-dir']         = $link['root']. 'template/';
    $link['locale-dir']           = $link['root']. 'locale/';
    $link['model-dir']            = $link['root']. 'model/';

//public directory
    $link['style-dir']            = cfg::getStyleDir();
    $link['img-dir']              = cfg::getImageDir();
    $link['js-dir']               = cfg::getScriptDir();

///page location variable starts here
    $mainmenu = $link['template-dir'].'mainmenu.template.php';
    $footer = $link['template-dir'].'footer.template.php';


    if($pathname != null){
        if(!empty($link[$pathname])){
            return $link[$pathname];
        }
    }

    return $link;
}