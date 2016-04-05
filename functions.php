<?php
/**
 * Copyright (c) AvikB, some rights reserved.
 * Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 * Spelling mistakes and fixes from phred and other community memebers.
 */

/**
 * @todo ability to automatically update user rank to elite when submitted add-on exceeds defined limit
 * @todo ability to remove mod approval for addons when a user is trusted
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

//All links defined here. MODIFY IT WHEN FOLDER/SITE STRUCTURE CHANGES!
$link = array();
$link['url'] = 'http://'.$_SERVER['HTTP_HOST']."/";
$link['root'] = $_SERVER['DOCUMENT_ROOT']."/";
$link['download'] = $link['url'].'download/';
$link['rss'] = $link['url'].'rss/';
$link['home'] = $link['url'];
$link['forum'] = $link['url'].'forum/';
$link['admin']['admin-panel'] = $link['url'].'admin-panel/';
$link['admin']['forum-panel'] = $link['forum'].'?action=admin';
$link['login'] = $link['forum'].'?action=login';
$link['support'] = $link['url'].'support/';
$link['addon']['home'] = $link['url'].'addons/';
$link['addon']['dashboard'] = $link['url'].'dashboard/';
$link['help'] = $link['url'].'help/';
$link['release-note'] = $link['url'].'release-note/';
$link['press'] = $link['url'].'press/';
$link['devapi'] = $link['url'].'api/';
$link['bugreport'] = $link['url'].'bug/';
$link['redirect'] = $link['url'].'out/';
$link['404'] = $link['root']."error/404.php";
$link['kb'] = $link['url'].'kb/';

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

//Default language file. DO NOT REMOVE IT!
require_once $link['root'].'includes/languages/en-us.php';

//Load other classes and database login info
require_once $link['root'].'classes/Format.php';
require_once $link['root'].'classes/Validation.php';
require_once $link['root'].'setting.php';


//Create the logout link..... THIS SHOULD NOT BE DECLARED BEFORE SSI.php SCRIPT!
$link['logout'] = $link['forum'].'index.php?action=logout;'.$context['session_var'].'='.$context['session_id'];


//Gets website setting !DO NOT REMOVE IT!
$setting = getSetting();

//Save current page url into session for login/logout redirect............ well it does not work anyway! could be a SMF Bug.
if(!strpos(currentUrl(), 'login') && !strpos(currentUrl(), 'includes') && !strpos(currentUrl(), 'styles') && !strpos(currentUrl(), 'img')) {
	$_SESSION['login_url'] = currentUrl();
	$_SESSION['logout_url'] = currentUrl();
	$_SESSION['old_url'] = currentUrl();
}

//creates an array from the URI
$params = array_map('strtolower', explode("/", $_SERVER['REQUEST_URI']));

//Get user avatar or use the default avatar
$user_avatar = ($context['user']['avatar'] != null) ? $context['user']['avatar']['href'] : $link['url'].'img/usersmall.jpg';

//Get the musicbee satble and beta release data from the API page
$releaseData = json_decode(file_get_contents($link['url'].'api.get.php?type=json&action=release-info'));

//Contains EVERYTHING in single multidimensional array! DO NOT REMOVE IT!
$mb = array(
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
				'language'        => $context['user']['language'],
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

		'main_menu'            => array(
				'web-admin'       => array(
						'title'       => $lang['9'],
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
										'title' => '<p class="user_info">'.$lang['19'].$context['user']['username'].'</p>',
								),
								'line1'        => array('title' => $lang['line'],),
								'admin-panel'  => array(
										'title'       => $lang['7'],
										'href'        => $link['admin']['admin-panel'],
										'icon'        => $lang['20'],
										'restriction' => 'admin',
								),
								'forum-admin'  => array(
										'title'       => $lang['8'],
										'href'        => $link['admin']['forum-panel'],
										'icon'        => $lang['21'],
										'restriction' => 'admin',
								),
								'line2'        => array('title' => $lang['line'],),
								'sign-out'     => array(
										'title' => $lang['10'],
										'href'  => $link['logout'],
										'icon'  => $lang['23'],
								),
						),
				),
				'home'            => array(
						'title'    => $lang['1'],
						'href'     => $link['home'],
						'sub_menu' => array(),
				),
				'download'        => array(
						'title'    => $lang['2'],
						'href'     => $link['download'],
						'sub_menu' => array(),
				),
				'add-ons'         => array(
						'title'    => $lang['3'],
						'href'     => $link['addon']['home'],
						'sub_menu' => array(
								'skins'        => array(
										'title' => $lang['11'],
										'href'  => $link['addon']['home']."s/?type=skins",
										'icon'  => $lang['24'],
										'desc'  => $lang['description_1'],
								),
								'plugins'      => array(
										'title' => $lang['12'],
										'href'  => $link['addon']['home']."s/?type=plugins",
										'icon'  => $lang['25'],
										'desc'  => $lang['description_2'],
								),
								'visualiser'   => array(
										'title' => $lang['13'],
										'href'  => $link['addon']['home']."s/?type=visualiser",
										'icon'  => $lang['26'],
										'desc'  => $lang['description_3'],
								),
								'theater-mode' => array(
										'title' => $lang['15'],
										'href'  => $link['addon']['home']."s/?type=theater-mode",
										'icon'  => $lang['28'],
										'desc'  => $lang['description_5'],
								),
								'misc'         => array(
										'title' => $lang['16'],
										'href'  => $link['addon']['home']."s/?type=misc",
										'icon'  => $lang['29'],
										'desc'  => $lang['description_6'],
								),
						),
				),
				'forum'           => array(
						'title'    => $lang['4'],
						'href'     => $link['forum'],
						'sub_menu' => array(),
				),
				'help'            => array(
						'title'    => $lang['5'],
						'href'     => $link['help'],
						'sub_menu' => array(),
				),
		),

		'musicbee_download' => array(
				'stable' => array(
						'appname'      => isset($releaseData[0]->appname) ? $releaseData[0]->appname : "NA",
						'version'      => isset($releaseData[0]->version) ? $releaseData[0]->version : "NA",
						'release_date' => isset($releaseData[0]->release_date) ? $releaseData[0]->release_date : "NA",
						'supported_os' => isset($releaseData[0]->supported_os) ? $releaseData[0]->supported_os : "NA",
						'download'     => array(
								'available' => isset($releaseData[0]->available) ? $releaseData[0]->available : 0,
								'installer' => array(
										'link1' => isset($releaseData[0]->DownloadLink) ? $releaseData[0]->DownloadLink : "NA",
										'link2' => isset($releaseData[0]->MirrorLink1) ? $releaseData[0]->MirrorLink1 : null,
										'link3' => isset($releaseData[0]->MirrorLink2) ? $releaseData[0]->MirrorLink2 : null,
								),
								'portable'  => array(
										'link1' => isset($releaseData[0]->PortableLink) ? $releaseData[0]->PortableLink : "NA",
								),

						),
				),

				'beta' => array(
						'appaname'     => isset($releaseData[1]->appname) ? $releaseData[1]->appname : "NA",
						'version'      => isset($releaseData[1]->version) ? $releaseData[1]->version : "NA",
						'release_date' => isset($releaseData[1]->release_date) ? $releaseData[1]->release_date : "NA",
						'supported_os' => isset($releaseData[1]->supported_os) ? $releaseData[1]->supported_os : "NA",
						'download'     => array(
								'available' => isset($releaseData[1]->available) ? $releaseData[1]->available : 0,
								'link1'     => isset($releaseData[1]->DownloadLink) ? $releaseData[1]->DownloadLink : "NA",
						),
						'message'      => isset($releaseData[1]->message) ? $releaseData[1]->message : null,
				),
		),

		'view_range' => array(
				'addon_view_range'         => 20,
				'dashboard_all_view_range' => 20,
		),
);

//var_dump($mb);


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
		die('{"status": "0", "data": "'.$lang['LOGIN_NEED'].'"}');
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
if(!$mb['user']['is_guest']) {
	require_once $link['root'].'classes/Member.php';
	$memberData = new Member();

	if($memberData->memberInfo($mb['user']['id'])['rank'] == null) {
		$permission = 10;
		if($mb['user']['is_admin']) {
			$permission = 1; //permission level 1 means the member is admin
		} elseif($mb['user']['is_mod']) {
			$permission = 2; //permission level 2 means the member is mod
		}
		//Create an Addon dashboard account for the user
		if($memberData->createDashboardAccount($mb['user']['id'], $permission, $mb['user']['name'])) {
			$userinfo = $memberData->memberInfo($mb['user']['id']);
		} else {

		}
	} else {
		//check if forum username updated,
		$userinfo = $memberData->memberInfo($mb['user']['id']);

		if($userinfo['membername'] != $mb['user']['username']) {
			if($memberData->updateDashboardAccount($mb['user']['id'], $mb['user']['username'])) {
				$userinfo = $memberData->memberInfo($mb['user']['id']);
			}
		}
	}

	$_SESSION['memberinfo'] = array(
			'membername' => $userinfo['membername'],
			'memberid'   => $userinfo['ID_MEMBER'],
			'rank'       => Validation::rankName($userinfo['rank']),
			'rank_raw'   => $userinfo['rank'],
	);

	//Set the user ranks and permissions once we get it from database
	$mb['user']['is_elite'] = ($userinfo['rank']==5)? true : false;
	$mb['user']['is_newbie'] = ($userinfo['rank']==10)? true : false;
	$mb['user']['rank_name'] = Validation::rankName($userinfo['rank']);
	$mb['user']['need_approval'] = ($userinfo['rank']>5)? true : false;
} else {
	$_SESSION['memberinfo'] = null;
}

//var_dump($mb);


///page location variable starts here
$mainmenu = $link['root'].'views/mainmenu.template.php';
$footer = $link['root'].'views/footer.template.php';


/** @var database connection $connection */
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

			return true;
		} catch(PDOException $e) {
		}
	}

	return false;
}


/**
 * @param $value
 * @param $type
 *
 * @return null|string
 * MusicBee stable and beta release Info
 */
function getVersionInfo($value, $type) {
	global $connection, $lang;
	if(databaseConnection()) {
		try {
			if($type == "byId") {
				$sql = "SELECT * FROM ".SITE_MB_ALL_VERSION_TBL." WHERE ID_ALLVERSIONS=:value";
			} elseif($type == "byVersion") {
				$sql = "SELECT * FROM ".SITE_MB_ALL_VERSION_TBL." WHERE version=:value";
			} elseif($type == "byCurrentVersion") {
				$sql = "SELECT * FROM ".SITE_MB_CURRENT_VERSION_TBL." WHERE ID_VERSION=:value";
			} elseif($type == "byAllReleases") {
				$sql = "SELECT * FROM ".SITE_MB_ALL_VERSION_TBL." ORDER BY version DESC";
			}
			$statement = $connection->prepare($sql);
			if($type != "byAllReleases") {
				$statement->bindValue(':value', $value);
			}
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if(count($result) > 0) {
				return $result; //Get the availablity first 1= available, 0=already disabled
			} else {
				if($type == "byId") {
					return $lang['AP_NO_RECORD'];
				} //store the error message in the variable
				elseif($type == "byVersion") {
					return null;
				} //if we are checking using version we want to send null. since we use count() method for result
			}
		} catch(Exception $e) {
			return "Something went wrong. ".$e; //store the error message in the variable
		}
	}
}

/**
 * Get all Website setting
 *
 * @return array
 */
function getSetting() {
	global $connection;

	if(databaseConnection()) {
		try {
			$sql = "SELECT * FROM ".SETTINGS;
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
 * Gets the current page URL
 *
 * @return string
 */
function currentUrl() {
	$pageURL = 'http';
	if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}

	return $pageURL;
}


/**
 * ONLY FOR DEVELOPMENT PURPOSE
 * Shows the parsed mysql query with value
 *
 * @param $query
 * @param $params
 *
 * @return mixed
 */
function showQuery($query, $params) {
	$keys = array();
	$values = array();

	# build a regular expression for each parameter
	foreach($params as $key => $value) {
		if(is_string($key)) {
			$keys[] = '/:'.$key.'/';
		} else {
			$keys[] = '/[?]/';
		}

		if(is_numeric($value)) {
			$values[] = intval($value);
		} else {
			$values[] = '"'.$value.'"';
		}
	}

	$query = preg_replace($keys, $values, $query, 1, $count);

	return $query;
}