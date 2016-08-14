<?php
/**
 * Copyright (c) AvikB, some rights reserved.
 * Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 * Spelling mistakes and fixes from community members.
 */

//use microtime to get page loadtime
$startScriptTime = microtime(true);

//Don't do anything if functions.php is already loaded.
if(defined('MB_FUNC')) {
	return true;
}
define('MB_FUNC', 'COMMON_FUNCTION');

//Start a new session if no session detected..... WARNING! IT REQUIRES PHP 5.4 OR LATER
if(session_status() == PHP_SESSION_NONE) {
	session_start();
}

$secure = (isSecure())?'https://':'http://';

//All links defined here. MODIFY IT WHEN FOLDER/SITE STRUCTURE CHANGES!
$link                         = array();
$link['root']                 = dirname(__FILE__) ."/";
$link['url']                  = $secure.$_SERVER['HTTP_HOST']."/";
$link['favicon']              = $link['url']."favicon.ico";
$link['download']             = $link['url'].'downloads/';
$link['rss']                  = $link['url'].'rss/';
$link['home']                 = $link['url'];
$link['forum']                = $link['url'].'forum/';
$link['admin']['forum-panel'] = $link['forum'].'?action=admin';
$link['login']                = $link['forum'].'?action=login';
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

//creates an array from the URI
$params = array_map('strtolower', explode("/", $_SERVER['REQUEST_URI']));

//Error code for knowledge base page
$errorCode = array(
	'ADMIN_ACCESS'      => '101',
	'LOGIN_MUST'        => '102',
	'FORUM_INTEGRATION' => '103',
	'NOT_FOUND'         => '104',
	'NO_DIRECT_ACCESS'  => '105',
	'MOD_ACCESS'        => '106',
);

//SMF SSI.php for forum integration. This is the core of the site's authentication. DO NOT REMOVE IT!
require_once $link['root'].'forum/SSI.php';

//Forum integration is must, if it is not initialized before this then throw an error
if(!isset($context)) {
	header('Location: '.$link['kb'].'?code='.$errorCode['FORUM_INTEGRATION']);
}

//Create the logout link..... THIS SHOULD NOT BE DECLARED BEFORE SSI.php SCRIPT!
$link['logout'] = $link['forum'].'index.php?action=logout;'.$context['session_var'].'='.$context['session_id'];

//Language array
$lang = array();

//List of all indexed language files
require_once $link['root'].'includes/languages/lang.list.php';

//Default language file. DO NOT REMOVE IT!
include_once $link['root'].'includes/languages/en-us.php';

//Default language is english
$language = setLanguage();
if(!empty($language)) {
	if(file_exists($link['root'].'includes/languages/'.$language['filename']) && $language['meta'] != 'en-us') {
		/** @noinspection PhpIncludeInspection */
		include_once $link['root'].'includes/languages/'.$language['filename'];
	}
}


//Load other classes and database login info
require_once $link['root'].'classes/Format.php';
require_once $link['root'].'classes/Validation.php';
require_once $link['root'].'setting.php';
require_once $link['root'].'classes/Help.php';

//Gets website setting !DO NOT REMOVE IT!
$setting = getSetting();
//Save current page url into session for login/logout redirect............ well it does not work anyway! could be a SMF Bug.
if(!strpos(currentUrl(), 'login') && !strpos(currentUrl(), 'includes') && !strpos(currentUrl(), 'styles') && !strpos(currentUrl(), 'img') && !strpos(currentUrl(), 'kb')) {
	$_SESSION['login_url']  = currentUrl();
	$_SESSION['logout_url'] = currentUrl();
	$_SESSION['old_url']    = currentUrl();
	$_SESSION['redirect']   = currentUrl();
}

$_SESSION['previous_page'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $link['url'];

// var_dump($_SESSION['redirect']);

//Get user avatar or use the default avatar
$user_avatar = ($context['user']['avatar'] != null) ? $context['user']['avatar']['href'] : $link['url'].'img/usersmall.jpg';

//Get the musicbee satble and beta release data
$releaseData['stable'] = getVersionInfo(0,'byCurrentVersion')[0];
$releaseData['beta']   = getVersionInfo(1,'byCurrentVersion')[0];


//Contains EVERYTHING in single multidimensional array! DO NOT REMOVE IT!
$mb = array(
	'website' => array(
		'ver'           => '1.2.3',
		'show_warning'  => false,
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
		'username'        => $context['user']['username'],
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
			'title'       => $lang['dashboard'],
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
					'title' => '<p class="user_info">'.sprintf($lang['hey_username'], $context['user']['username']).'</p>',
				),
				'line1'        => array('title' => $lang['line'],),
				'admin-panel'  => array(
					'title'       => $lang['web_admin'],
					'href'        => $link['addon']['dashboard'].'#admin_setting',
					'icon'        => $lang['20'],
					'restriction' => 'admin',
				),
				'forum-admin'  => array(
					'title'       => $lang['forum_admin'],
					'href'        => $link['admin']['forum-panel'],
					'icon'        => $lang['21'],
					'restriction' => 'admin',
				),
				'line2'        => array('title' => $lang['line'],),
				'sign-out'     => array(
					'title' => $lang['sign_out'],
					'href'  => $link['logout'],
					'icon'  => $lang['23'],
				),
			),
		),
		'download'        => array(
			'title'    => $lang['download'],
			'href'     => $link['download'],
			'sub_menu' => array(),
		),
		'add-ons'         => array(
			'title'    => $lang['addons'],
			'href'     => $link['addon']['home'],
			'sub_menu' => array(
				'1' => array(
					'title' => $lang['skins'],
					'href'  => $link['addon']['home']."s/?type=1",
					'icon'  => $lang['24'],
					'desc'  => $lang['description_1'],
					'id'    => 1,
				),
				'2' => array(
					'title' => $lang['plugins'],
					'href'  => $link['addon']['home']."s/?type=2",
					'icon'  => $lang['25'],
					'desc'  => $lang['description_2'],
					'id'    => 2,
				),
				'3' => array(
					'title' => $lang['visualizer'],
					'href'  => $link['addon']['home']."s/?type=3",
					'icon'  => $lang['26'],
					'desc'  => $lang['description_3'],
					'id'    => 3,
				),
				'4' => array(
					'title' => $lang['theater_mode'],
					'href'  => $link['addon']['home']."s/?type=4",
					'icon'  => $lang['28'],
					'desc'  => $lang['description_5'],
					'id'    => 4,
				),
				'5' => array(
					'title' => $lang['misc'],
					'href'  => $link['addon']['home']."s/?type=5",
					'icon'  => $lang['29'],
					'desc'  => $lang['description_6'],
					'id'    => 5,
				),
			),
		),
		'forum'           => array(
			'title'    => $lang['forum'],
			'href'     => $link['forum'],
			'sub_menu' => array(),
		),
		'help'            => array(
			'title'    => $lang['help'],
			'href'     => $link['faq'],
			'sub_menu' => array(
				'faq' => array(
					'title' => $lang['faq'],
					'href'  => $link['faq'],
					'icon'  => $lang['faq_icon'],
				),
				'api' => array(
					'title' => $lang['dev_api'],
					'href'  => $link['api'],
					'icon'  => $lang['code_icon'],
				),
				'line2'        => array('title' => $lang['line'],),
				'release-note' => array(
					'title' => $lang['release-note'],
					'href'  => $link['release-note'],
					'icon'  => $lang['note_icon'],
				),
				'press' => array(
					'title' => $lang['press'],
					'href'  => $link['press'],
					'icon'  => $lang['press_icon'],
				),
				'line3'        => array('title' => $lang['line'],),
				'bug' => array(
					'title' => $lang['report_bug'],
					'href'  => $link['bugreport'],
					'icon'  => $lang['bug_icon'],
					'hide'  => true,
				),
				'wiki' => array(
					'title' => $lang['mb_wiki'],
					'href'  => $setting['wikiaLink'],
					'icon'  => $lang['wiki_icon'],
					'target'=> '_blank',
					'hide'  => true,
				),
			),
		),
	),

	'musicbee_download' => array(
		'stable' => array(
			'appname'      => isset($releaseData['stable']['appname'])          ? $releaseData['stable']['appname'] : "NA",
			'version'      => isset($releaseData['stable']['version'])          ? $releaseData['stable']['version'] : "NA",
			'release_date' => isset($releaseData['stable']['release_date'])     ? $releaseData['stable']['release_date'] : "NA",
			'supported_os' => isset($releaseData['stable']['supported_os'])     ? $releaseData['stable']['supported_os'] : "NA",
			'download'     => array(
				'available' => isset($releaseData['stable']['available'])       ? $releaseData['stable']['available'] : 0,
				'installer' => array(
					'link1' => isset($releaseData['stable']['DownloadLink'])    ? $releaseData['stable']['DownloadLink'] : "NA",
					'link2' => isset($releaseData['stable']['MirrorLink1'])     ? $releaseData['stable']['MirrorLink1'] : null,
					'link3' => isset($releaseData['stable']['MirrorLink2'])     ? $releaseData['stable']['MirrorLink2'] : null,
				),
				'portable'  => array(
					'link1' => isset($releaseData['stable']['PortableLink'])    ? $releaseData['stable']['PortableLink'] : "NA",
				),
			),
		),

		'beta' => array(
			'appname'      => isset($releaseData['beta']['appname'])            ? $releaseData['beta']['appname'] : "NA",
			'version'      => isset($releaseData['beta']['version'])            ? $releaseData['beta']['version'] : "NA",
			'release_date' => isset($releaseData['beta']['release_date'])       ? $releaseData['beta']['release_date'] : "NA",
			'supported_os' => isset($releaseData['beta']['supported_os'])       ? $releaseData['beta']['supported_os'] : "NA",
			'download'     => array(
				'available' => isset($releaseData['beta']['available'])         ? $releaseData['beta']['available'] : 0,
				'link1'     => isset($releaseData['beta']['DownloadLink'])      ? $releaseData['beta']['DownloadLink'] : "NA",
			),
			'message'      => isset($releaseData['beta']['message'])            ? $releaseData['beta']['message'] : null,
		),

		'patch' => getVersionInfo(2, 'byCurrentVersion')[0],
	),

	'help' => Help::getHelp(),

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
if(!$mb['user']['is_admin'] && !empty($admin_only)) {
	header('Location: '.$link['kb'].'?code='.$errorCode['ADMIN_ACCESS']);
} elseif(!$mb['user']['can_mod'] && !empty($mod_only)) {
	if(!empty($json_response)) {
		die('{"status": "0", "data": "'.$lang['dashboard_err_1'].'"}');
	} else {
		header('Location: '.$link['kb'].'?code='.$errorCode['MOD_ACCESS']);
	}
} elseif($mb['user']['is_guest'] && !empty($no_guests)) {
	if(!empty($json_response)) {
		die('{"status": "0", "data": "'.$lang['err_login_required'].'"}');
	} else {
		header('Location: '.$link['kb'].'?code='.$errorCode['LOGIN_MUST']);
	}
}

//if $no_directaccess is set, no direct access is allowed
if(!empty($no_directaccess)) {
	if(!@$_SERVER['HTTP_REFERER']) {
		header('Location: '.$link['kb'].'?code='.$errorCode['NO_DIRECT_ACCESS']);
	}
}

/**
 * If the User has an account in forum but not for the dashboard then create one,
 * and set dashoard user info in session
 */
require_once $link['root'].'classes/Member.php';
$member = new Member();


///page location variable starts here
$mainmenu = $link['root'].'views/mainmenu.template.php';
$footer = $link['root'].'views/footer.template.php';


$connection = null;
/**
 * @return bool
 * Checks and creates database connection.
 */
function databaseConnection() {
	global $connection;
	//if connection already exists
	if($connection != null) {
		return true;
	} else {
		try {
			$connection = new PDO('mysql:host='.DB_HOST.';dbname='.SITE_DB_NAME.';charset=utf8', SITE_DB_USER, SITE_DB_PASS);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connection->exec('set session sql_mode = traditional');
			$connection->exec('set session innodb_strict_mode = on');

			return true;
		} catch(PDOException $e) {
		}
	}

	return false;
}


/**
 * MusicBee stable and beta release Info
 *
 * @param $value
 * @param $type
 *
 * @return null|string
 */
function getVersionInfo($value, $type) {
	global $connection, $lang, $db_info;
	if(databaseConnection()) {
		try {
			if($type == "byId") {
				$sql = "SELECT * FROM {$db_info['mb_all']} WHERE ID_ALLVERSIONS=:value";
			} elseif($type == "byVersion") {
				$sql = "SELECT * FROM {$db_info['mb_all']} WHERE version=:value";
			} elseif($type == "byCurrentVersion") {
				$sql = "SELECT * FROM {$db_info['mb_current']} WHERE ID_VERSION=:value";
			} elseif($type == "byAllReleases") {
				$sql = "SELECT * FROM {$db_info['mb_all']} ORDER BY version DESC";
			}
			$statement = $connection->prepare($sql);

			if($type != "byAllReleases")
				$statement->bindValue(':value', $value);

			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);

			if(count($result) > 0)
			{
				return $result; //Get the availablity first 1= available, 0=already disabled
			}
			else
			{
				if($type == "byId")
				{
					return $lang['no_record'];
				}
				elseif($type == "byVersion")
				{
					return null;
				} //if we are checking using version we want to send null. since we use count() method for result
			}
		} catch(Exception $e) {
			return "Something went wrong. ".$e; //store the error message in the variable
		}
	}
	return null;
}

/**
 * Get all Website setting
 *
 * @return array
 */
function getSetting() {
	global $connection, $db_info;

	if(databaseConnection()) {
		try {
			$sql = "SELECT * FROM {$db_info['settings_tbl']}";
			$statement = $connection->prepare($sql);
			$statement->execute();
			$result = array_map('reset', array_map('reset', $statement->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC)));

			$result['showPgaeLoadTime'] = ($result['showPgaeLoadTime'] == 1) ? true : false;
			$result['addonSubmissionOn'] = ($result['addonSubmissionOn'] == 1) ? true : false;
			$result['imgurUploadOn'] = ($result['imgurUploadOn'] == 1) ? true : false;


			return $result;
		} catch(Exception $e) {
		}
	}
}


/**
 * Set language cookie and returns language file details
 *
 * @return mixed
 */
function setLanguage() {
	if(isset($_GET['lang'])) {
		$language = getLanguageFileName($_GET['lang']);
	} elseif(isset($_COOKIE['lang'])) {
		$language = getLanguageFileName($_COOKIE['lang']);
	} else {
		$language = getLanguageFileName('en-us');
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
function getLanguageFileName($lang) {
	global $lang_filelist;

	if(array_key_exists($lang, $lang_filelist)) {
		return $lang_filelist[$lang];
	}
}

/**
 * Check if the server has SSL or not
 * 
 * @return boolean
 */
function isSecure() {
	return
		(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
		|| $_SERVER['SERVER_PORT'] == 443;
}



/**
 * Gets the current page URL
 *
 * @return string
 */
function currentUrl() {
	global $secure ;

	$pageURL = $secure;
	if($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}

	return $pageURL;
}
