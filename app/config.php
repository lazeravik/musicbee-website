<?php


require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";
use App\Lib\Utility\Config as cfg;

//All links defined here. MODIFY IT WHEN FOLDER/SITE STRUCTURE CHANGES!
$link                         = array();
$link['root']                 = cfg::getRootDir().'/app/';
$link['url']                  = cfg::getHttpUrl();
$link['app-url']              = $link['url'].'app/';
$link['favicon']              = $link['url']."favicon.ico";
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
$link['style-dir']            = cfg::getStyleDir();
$link['img-dir']              = cfg::getImageDir();
$link['js-dir']               = cfg::getScriptDir();
$link['incl-dir']             = $link['root'].'includes/';


$db_info = array();
$db_info['host']              = 'localhost';
$db_info['db_name']           = 'mb_web';
$db_info['db_username']       = 'root';
$db_info['db_password']       = '';
$db_info['db_prefix']         = 'mb_';
$db_info['addon_tbl']         = $db_info['db_prefix'].'addons';
$db_info['member_tbl']        = $db_info['db_prefix'].'member';
$db_info['likes_tbl']         = $db_info['db_prefix'].'likes';
$db_info['download_stat_tbl'] = $db_info['db_prefix'].'download_stat';
$db_info['settings_tbl']      = $db_info['db_prefix'].'settings';
$db_info['mb_all']            = $db_info['db_prefix'].'allversions';
$db_info['mb_current']        = $db_info['db_prefix'].'current_version';
$db_info['help']              = $db_info['db_prefix'].'help';
