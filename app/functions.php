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
exit();
//use microtime to get page loadtime
$startScriptTime = microtime(true);


global $langList, $link, $locale;


include_once '../forum/SSI.php';
include_once '../vendor/autoload.php';
include_once 'locale/lang.list.php';
include_once 'config/paths.php';
include_once 'config/database.php';
require_once 'libraries/gettext/gettext.inc.php';

use App\Lib\Utility\Route;
use App\Lib\Utility\Router;
use App\Lib\Utility\LanguageManager;
use App\Lib\Utility\Config as cfg;
use App\Lib\Utility\Session;

Session::init();

//Error code for knowledge base page
$errorCode = array(
    'ADMIN_ACCESS'      => '101',
    'LOGIN_MUST'        => '102',
    'FORUM_INTEGRATION' => '103',
    'NOT_FOUND'         => '104',
    'NO_DIRECT_ACCESS'  => '105',
    'MOD_ACCESS'        => '106',
);

$router = new Router();
$router->addRoute(new Route("/", "Home"));
$router->addRoute(new Route("/downloads", "Downloads"));
$router->addRoute(new Route("/help/awesome", function(){printf('hello!');

}));
//Perform the routing!
$router->route();


/**
 * Change language on request and set cookie
 */
LanguageManager::init($router->getLanguageParamFromUrl(), $langList);
$locale = LanguageManager::getRequestedLanguage();
LanguageManager::setLanguage($locale);

if (LanguageManager::matchLanguage() == "/" ||
    strtolower(LanguageManager::getFromLanguageArrayItem()) == "") {
    $urltoRedirect = $link['url'] .
        $router->generateUrlWithLangParam(
        $locale,
        LanguageManager::getFromLanguageArrayKey()
    );
    // 301 Moved Permanently to a localized url
    header('Location: '.$urltoRedirect, true, 301);
}



//Forum integration is must, if it is not initialized before this then throw an error
if (!isset($context)) {
    header('Location: '.$link['kb'].'?code='.$errorCode['FORUM_INTEGRATION']);
}


//Gets website setting !DO NOT REMOVE IT!
$setting = null;

//Save current page url into session for login/logout redirect............
//well it does not work anyway! could be a SMF Bug.
if (!strpos(cfg::currentUrl(), 'login')
    && !strpos(cfg::currentUrl(), 'logout')
    && !strpos(cfg::currentUrl(), 'includes')
    && !strpos(cfg::currentUrl(), 'styles')
    && !strpos(cfg::currentUrl(), 'img')
    && !strpos(cfg::currentUrl(), 'kb')) {
    $_SESSION['login_url']  = cfg::currentUrl();
    $_SESSION['logout_url'] = cfg::currentUrl();
    $_SESSION['old_url']    = cfg::currentUrl();
    $_SESSION['redirect']   = cfg::currentUrl();
}

$_SESSION['previous_page'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $link['url'];

//Get user avatar or use the default avatar
$user_avatar = $context['user']['avatar'] != null ? $context['user']['avatar']['href']
                                                  : $link['img-dir'].'usersmall.jpg';

//Get the musicbee satble and beta release data
//$releaseData['stable'] = getVersionInfo(0, 'byCurrentVersion')[0];
//$releaseData['beta']   = getVersionInfo(1, 'byCurrentVersion')[0];

//Contains EVERYTHING in single multidimensional array! DO NOT REMOVE IT!
$mb = array(
    'website' => array(
        'ver'           => '1.9.5',
        'show_warning'  => false,
        'is_test'		=> true,
        'github_link'   => 'https://github.com/Avik-B/mb_web/',
    ),
    'charset'        => 'UTF-8',
    'user'           => array(
        'id'              => $context['user']['id'],
        'is_logged'       => $context['user']['is_logged'],
        'is_guest'        => $context['user']['is_guest'],
        'is_admin'        => $context['user']['is_admin'],
        'is_mod'          => $context['user']['is_mod'],
        'is_elite'        => false,
        'is_newbie'       => false,
        'rank_name'       => null,
        'need_approval'   => true,
        'can_mod'         => $context['user']['can_mod'],
        'username'        => $context['user']['name'],
        'email'           => $context['user']['email'],
        'name'            => $context['user']['name'],
        'messages'        => $context['user']['messages'],
        'unread_messages' => $context['user']['unread_messages'],
        'avatar'          => $user_avatar,
    ),
    'session_var'    => $context['session_var'],
    'session_id'     => $context['session_id'],
    'current_time'   => array(
        'date'      => date("F j, Y"),
        'date_time' => date("F j, Y, g:i a"),
    ),

    'main_menu' => array(
        'dashboard'       => array(
            'title'       => __("Dashboard"),
            'href'        => $link['addon']['dashboard'],
            'restriction' => 'login',
            'sub_menu'    => array(),
        ),
        'member-panel'    => array(
            'title'       => '<img src="'.$user_avatar.'" class="user_avatar">',
            'href'        => $link['forum'].'?action=profile',
            'restriction' => 'login',
            'sub_menu'    => array(
                'user-profile' => array(
                    'title' => ''.sprintf(__('Hey, %1$s'), $context['user']['name']).'',
                    'href'        => $link['forum'].'?action=profile',
                ),
                'line1'        => array('title' => "<hr class=\"line\"/>",),
                'admin-panel'  => array(
                    'title'       => __("Web Admin"),
                    'href'        => $link['addon']['dashboard'].'#admin_setting',
                    'icon'        => "<i class=\"fa fa-desktop\"></i>",
                    'restriction' => 'admin',
                ),
                'forum-admin'  => array(
                    'title'       => __("Forum Admin"),
                    'href'        => $link['admin']['forum-panel'],
                    'icon'        => "<i class=\"fa fa-comments\"></i>",
                    'restriction' => 'admin',
                ),
                'line2'        => array('title' => "<hr class=\"line\"/>",),
                'sign-out'     => array(
                    'title' => __("Sign Out"),
                    'href'  => $link['logout'],
                    'icon'  => "<i class=\"fa fa-sign-out\"></i>",
                ),
            ),
        ),
        'download'        => array(
            'title'    => __("Download"),
            'href'     => $link['download'],
            'sub_menu' => array(),
        ),
        'add-ons'         => array(
            'title'    => __("Add-ons"),
            'href'     => $link['addon']['home'],
            'sub_menu' => array(
                '1' => array(
                    'title' => __("Skins"),
                    'href'  => $link['addon']['home']."s/?type=1",
                    'icon'  => "<i class=\"fa fa-paint-brush\"></i>",
                    'desc'  => __("Make MusicBee look the way you want"),
                    'id'    => 1,
                ),
                '2' => array(
                    'title' => __("Plugins"),
                    'href'  => $link['addon']['home']."s/?type=2",
                    'icon'  => "<i class=\"fa fa-plug\"></i>",
                    'desc'  => __("Add features/functionality to MusicBee"),
                    'id'    => 2,
                ),
                '3' => array(
                    'title' => __("Visualizer"),
                    'href'  => $link['addon']['home']."s/?type=3",
                    'icon'  => "<i class=\"fa fa-bar-chart\"></i>",
                    'desc'  => __("Get colorful visualizers for an eye pleasing experience"),
                    'id'    => 3,
                ),
                '4' => array(
                    'title' => __("Theater Mode"),
                    'href'  => $link['addon']['home']."s/?type=4",
                    'icon'  => "<i class=\"fa fa-arrows-alt\"></i>",
                    'desc'  => __("Get a full theater mode experience for MusicBee"),
                    'id'    => 4,
                ),
                '5' => array(
                    'title' => __("Misc"),
                    'href'  => $link['addon']['home']."s/?type=5",
                    'icon'  => "<i class=\"fa fa-ellipsis-h\"></i>",
                    'desc'  => __("Other useful add-ons for enhancing your MusicBee experience"),
                    'id'    => 5,
                ),
            ),
        ),
        'forum'           => array(
            'title'    => __("Forum"),
            'href'     => $link['forum'],
            'sub_menu' => array(),
        ),
        'help'            => array(
            'title'    => __("Help"),
            'href'     => $link['faq'],
            'sub_menu' => array(
                'faq' => array(
                    'title' => __("FAQ & Help"),
                    'href'  => $link['faq'],
                    'icon'  => "<i class=\"fa fa-question\"></i>",
                ),
                'api' => array(
                    'title' => __("Developer API"),
                    'href'  => $link['api'],
                    'icon'  => "<i class=\"fa fa-code\"></i>",
                ),
                'line2'        => array('title' => "<hr class=\"line\"/>",),
                'release-note' => array(
                    'title' => __("Release Notes"),
                    'href'  => $link['release-note'],
                    'icon'  => "<i class=\"fa fa-sticky-note-o\"></i>",
                ),
                'press' => array(
                    'title' => __("Press & Media"),
                    'href'  => $link['press'],
                    'icon'  => "<i class=\"fa fa-bullhorn\"></i>",
                ),
                'line3'        => array('title' => "<hr class=\"line\"/>",),
                'bug' => array(
                    'title' => __("Report a bug"),
                    'href'  => $link['bugreport'],
                    'icon'  => "<i class=\"fa fa-bug\"></i>",
                    'hide'  => true,
                ),
                'wiki' => array(
                    'title' => __("MusicBee Wiki"),
                    'href'  => $setting['wikiaLink'],
                    'icon'  => "<i class=\"fa fa-wikipedia-w\"></i>",
                    'target'=> '_blank',
                    'hide'  => true,
                ),
            ),
        ),
    ),

    'musicbee_download' => array(
        'stable' => array(
            'appname'      => isset($releaseData['stable']['appname'])      ? $releaseData['stable']['appname'] : "NA",
            'version'      => isset($releaseData['stable']['version'])      ? $releaseData['stable']['version'] : "NA",
            'release_date' => isset($releaseData['stable']['release_date']) ? $releaseData['stable']['release_date'] : "NA",
            'supported_os' => isset($releaseData['stable']['supported_os']) ? $releaseData['stable']['supported_os'] : "NA",
            'download'     => array(
                'available' => isset($releaseData['stable']['available'])   ? $releaseData['stable']['available'] : 0,
                'installer' => array(
                    'link1' => isset($releaseData['stable']['DownloadLink'])? $releaseData['stable']['DownloadLink'] : "NA",
                    'link2' => isset($releaseData['stable']['MirrorLink1']) ? $releaseData['stable']['MirrorLink1'] : null,
                    'link3' => isset($releaseData['stable']['MirrorLink2']) ? $releaseData['stable']['MirrorLink2'] : null,
                ),
                'portable'  => array(
                    'link1' => isset($releaseData['stable']['PortableLink'])? $releaseData['stable']['PortableLink'] : "NA",
                ),
            ),
        ),

        'beta' => array(
            'appname'      => isset($releaseData['beta']['appname'])        ? $releaseData['beta']['appname'] : "NA",
            'version'      => isset($releaseData['beta']['version'])        ? $releaseData['beta']['version'] : "NA",
            'release_date' => isset($releaseData['beta']['release_date'])   ? $releaseData['beta']['release_date'] : "NA",
            'supported_os' => isset($releaseData['beta']['supported_os'])   ? $releaseData['beta']['supported_os'] : "NA",
            'download'     => array(
                'available' => isset($releaseData['beta']['available'])     ? $releaseData['beta']['available'] : 0,
                'link1'     => isset($releaseData['beta']['DownloadLink'])  ? $releaseData['beta']['DownloadLink'] : "NA",
            ),
            'message'      => isset($releaseData['beta']['message'])        ? $releaseData['beta']['message'] : null,
        ),

        //'patch' => getVersionInfo(2, 'byCurrentVersion')[0],
    ),

    //'help' => Help::getHelp(),

    'view_range' => array(
        'addon_view_range'         => 20,
        'dashboard_all_view_range' => 20,
        'release_all_view_range'   => 20,
    ),
);

//var_dump($mb['musicbee_download']);


/**
 * Maybe we don't wan't anyone except admin to see this, show error to anyone else. Or maybe
 * this is only available for logged in users. No guest is allowed kicked them to error page
 */
//if (!$mb['user']['is_admin'] && !empty($admin_only)) {
//    header('Location: '.$link['kb'].'?code='.$errorCode['ADMIN_ACCESS']);
//} elseif (!$mb['user']['can_mod'] && !empty($mod_only)) {
//    if (!empty($json_response)) {
//        die('{"status": "0", "data": "'.$lang['dashboard_err_1'].'"}');
//    } else {
//        header('Location: '.$link['kb'].'?code='.$errorCode['MOD_ACCESS']);
//    }
//} elseif ($mb['user']['is_guest'] && !empty($no_guests)) {
//    if (!empty($json_response)) {
//        die('{"status": "0", "data": "'.$lang['err_login_required'].'"}');
//    } else {
//        header('Location: '.$link['kb'].'?code='.$errorCode['LOGIN_MUST']);
//    }
//}

//if $no_directaccess is set, no direct access is allowed
if (!empty($no_directaccess)) {
    if (!@$_SERVER['HTTP_REFERER']) {
        header('Location: '.$link['kb'].'?code='.$errorCode['NO_DIRECT_ACCESS']);
    }
}

/**
 * If the User has an account in forum but not for the dashboard then create one,
 * and set dashoard user info in session
 */
//$member = new Member();




//
///**
// * Get all Website setting
// *
// * @return array
// */
//function getSetting()
//{
//    global $connection, $db_info;
//
//    if (databaseConnection()) {
//        try {
//            $sql = "SELECT * FROM {$db_info['settings_tbl']}";
//            $statement = $connection->prepare($sql);
//            $statement->execute();
//            $result = array_map('reset', array_map('reset', $statement->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC)));
//
//            $result['showPgaeLoadTime'] = ($result['showPgaeLoadTime'] == 1) ? true : false;
//            $result['addonSubmissionOn'] = ($result['addonSubmissionOn'] == 1) ? true : false;
//            $result['imgurUploadOn'] = ($result['imgurUploadOn'] == 1) ? true : false;
//
//
//            return $result;
//        } catch (Exception $e) {
//        }
//    }
//}
//





function addonUrlGenerator($addon_data)
{
    global $link, $mb;

    $type_blob = array_key_exists($addon_data['category'], $mb['main_menu']['add-ons']['sub_menu'])?
                                    $mb['main_menu']['add-ons']['sub_menu'][$addon_data['category']]['title'] :
                                    $addon_data['category'];

    $addon_link = $link['addon']['home']
        . Format::slug($type_blob). '/'
        . $addon_data['ID_ADDON'] . '/'
        . Format::slug($addon_data['addon_title']).'/';
    return $addon_link;
}
