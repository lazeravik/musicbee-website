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

require_once $_SERVER['DOCUMENT_ROOT'].'/forum/SSI.php';

// Don't do anything if already loaded.
if (defined('MB_FUNC')) return true;
define('MB_FUNC', 'COMMON_FUNCTION');
if(session_status() == PHP_SESSION_NONE) {session_start();}

$siteUrl 						= 'http://'.$_SERVER['HTTP_HOST']."/";
$siteRoot 						= $_SERVER['DOCUMENT_ROOT']."/";
//Error related pages and codes
$errorPage 						= $siteUrl . 'kb/';
$errorCode 						= array();
$errorCode['ADMIN_ACCESS'] 		= "101"; //if a non admin is trying to access a page
$errorCode['LOGIN_MUST'] 		= "102"; //User must logged in
$errorCode['FORUM_INTEGRATION']	= "103"; //Forum integration is not initialized
$errorCode['NOT_FOUND']			= "104"; //Page not found
$status['404']					= $siteRoot . "error/404.php";

require_once $siteRoot.'includes/languages/en-us.php';
//setting file contains setting variables, mysql database credentials, api ids, passwords etc
require_once $siteRoot.'setting.php';


/**
* Forum integration is must, if it is not initialized before this then throw an error
*/
if (!isset($context))
	header('Location: '. $errorPage.'?code='.$errorCode['FORUM_INTEGRATION']);
/**
* Maybe we don't wan't anyone except admin to see this, show error to anyone else. Or maybe
* this is only available for logged in users. No guest is allowed kicked them to error page
*/
if (!$user_info['is_admin'] && !empty($admin_only)) {
	header('Location: '. $errorPage.'?code='.$errorCode['ADMIN_ACCESS']);
} elseif ($user_info['is_guest'] && !empty($no_guests)) {
	if (!empty($json_response)) {
		die('{"status": "0", "data": "'.$lang['LOGIN_NEED'].'"}');
	} else {
		header('Location: '. $errorPage.'?code='.$errorCode['LOGIN_MUST']);
	}
}

/// page location variable starts here
$mainmenu 						= $siteRoot.'includes/mainmenu.template.php';
$footer 						= $siteRoot.'includes/footer.template.php';

$link 							= array();
$link['download'] 				= $siteUrl.'download/';
$link['rss'] 					= $siteUrl.'rss/';
$link['home'] 					= $siteUrl;
$link['forum'] 					= $siteUrl .'forum/';
$link['admin']['admin-panel'] 	= $siteUrl .'admin-panel/';
$link['admin']['forum-panel'] 	= $link['forum'] .'?action=admin';
$link['login'] 					= $link['forum'] . '?action=login';
$link['support'] 				= $siteUrl . 'support/';
$link['addon']['home'] 			= $siteUrl . 'addons/';
$link['addon']['dashboard'] 	= $siteUrl . 'addon-dashboard/';
$link['help'] 					= $siteUrl . 'help/';
$link['release-note'] 			= $siteUrl . 'release-note/';
$link['logout'] 				= $link['forum'] . 'index.php?action=logout;' . $context['session_var'] . '='. $context['session_id'] ;
$link['press'] 					= $siteUrl.'press/';
$link['devapi']					= $siteUrl.'api/';
$link['bugreport']				= $siteUrl.'bug/';

//get the MusicBee info from json api
$releaseData = json_decode(file_get_contents($siteUrl .'api.get.php?type=json&action=release-info'));

$release 						= array();
$release['stable']['appname'] 	= isset($releaseData[0]->appname)		?		$releaseData[0]->appname		:"...";
$release['stable']['version'] 	= isset($releaseData[0]->version)		?		$releaseData[0]->version 		:"...";
$release['stable']['date'] 		= isset($releaseData[0]->release_date)	?		$releaseData[0]->release_date	:"...";
$release['stable']['os'] 		= isset($releaseData[0]->supported_os)	?		$releaseData[0]->supported_os	:"...";
$release['stable']['link1'] 	= isset($releaseData[0]->DownloadLink)	?		$releaseData[0]->DownloadLink	:"...";
$release['stable']['link2'] 	= isset($releaseData[0]->MirrorLink1)	?		$releaseData[0]->MirrorLink1	:null;
$release['stable']['link3'] 	= isset($releaseData[0]->MirrorLink2)	?		$releaseData[0]->MirrorLink2	:null;
$release['stable']['link4']		= isset($releaseData[0]->PortableLink)	?		$releaseData[0]->PortableLink	:"...";
$release['stable']['available']	= isset($releaseData[0]->available)		?		$releaseData[0]->available 		:0;


$release['beta']['appname']		= isset($releaseData[1]->appname)		?		$releaseData[1]->appname		:"...";
$release['beta']['version']		= isset($releaseData[1]->version)		?		$releaseData[1]->version 		:"...";
$release['beta']['date'] 		= isset($releaseData[1]->release_date)	?		$releaseData[1]->release_date	:"...";
$release['beta']['os'] 			= isset($releaseData[1]->supported_os)	?		$releaseData[1]->supported_os	:"...";
$release['beta']['link1'] 		= isset($releaseData[1]->DownloadLink)	?		$releaseData[1]->DownloadLink	:"...";
$release['beta']['message'] 	= isset($releaseData[1]->message)		?		$releaseData[1]->message 		:null;
$release['beta']['available']	= isset($releaseData[1]->available)		?		$releaseData[1]->available 		:0;


$addon_view_range = 30;
/**
 * @var URI $params
 * creates an array from the URI
 */
$params = array_map('strtolower', explode("/", $_SERVER['REQUEST_URI']));


/**
* MainMenu generator
**/
$main_menu = array(
	'member-panel' => array(
		'title' => $lang['6'],
		'href'	=> "javascript:void(0)",
		'restriction' => 'login',
		'sub_menu' => array(
			'admin-panel' => array(
				'title' => $lang['7'],
				'href'	=> $link['admin']['admin-panel'],
				'icon'	=> $lang['20'],
				'restriction' => 'admin',
				),
			'forum-admin' => array(
				'title' => $lang['8'],
				'href'	=> $link['admin']['forum-panel'],
				'icon'	=> $lang['21'],
				'restriction' => 'admin',
				),
			'web-admin' => array(
				'title' => $lang['9'],
				'href'	=> $link['addon']['dashboard'],
				'icon'	=> $lang['22'],
				),
			'line' => array(
				'title' => $lang['line'],
				),
			'sign-out' => array(
				'title' => $lang['10'],
				'href'	=> $link['logout'],
				'icon'	=> $lang['23'],
				),
			),
		),
	'home' => array(
		'title' => $lang['1'],
		'href'	=> $link['home'],
		'sub_menu' => array(
			),
		),
	'download' => array(
		'title' => $lang['2'],
		'href'	=> $link['download'],
		'sub_menu' => array(
			),
		),
	'add-ons' => array(
		'title' => $lang['3'],
		'href'	=> $link['addon']['home'],
		'sub_menu' => array(
			'skins' => array(
				'title' => $lang['11'],
				'href'	=> $link['addon']['home'].Slug($lang['11']),
				'icon'	=> $lang['24'],
				'desc'	=> $lang['description_1'],
				),
			'plugins' => array(
				'title' => $lang['12'],
				'href'	=> $link['addon']['home'].Slug($lang['12']),
				'icon'	=> $lang['25'],
				'desc'	=> $lang['description_2'],
				),
			'visualiser' => array(
				'title' => $lang['13'],
				'href'	=> $link['addon']['home'].Slug($lang['13']),
				'icon'	=> $lang['26'],
				'desc'	=> $lang['description_3'],
				),
			'theater-mode' => array(
				'title' => $lang['15'],
				'href'	=> $link['addon']['home'].Slug($lang['15']),
				'icon'	=> $lang['28'],
				'desc'	=> $lang['description_5'],
				),
			'misc' => array(
				'title' => $lang['16'],
				'href'	=> $link['addon']['home'].Slug($lang['16']),
				'icon'	=> $lang['29'],
				'desc'	=> $lang['description_6'],
				),
			),
		),
	'forum' => array(
		'title' => $lang['4'],
		'href'	=> $link['forum'],
		'sub_menu' => array(
			),
		),
	'help' => array(
		'title' => $lang['5'],
		'href'	=> $link['help'],
		'sub_menu' => array(
			),
		),
	);

/** @var addon color $color_codes */
$color_codes = array(
	"default" => array(
		"name" => "Default",
		"value" => "#607D8B",
		),
	"red" => array(
		"name" => "Red",
		"value" => "#F44336",
		),
	"green" => array(
		"name" => "Green",
		"value" => "#4CAF50",
		),
	"dark-grey" => array(
		"name" => "Dark Grey",
		"value" => "#3A3A3A",
		),
	"pink" => array(
		"name" => "Pink",
		"value" => "#D042D2",
		),
	"violet" => array(
		"name" => "Violet",
		"value" => "#A000E2",
		),
	"navy-blue" => array(
		"name" => "Navy Blue",
		"value" => "#4152B5",
		),
	"blue" => array(
		"name" => "Blue",
		"value" => "#2196F3",
		),
	"yellow" => array(
		"name" => "Yellow",
		"value" => "#FF9800",
		),
	"brown" => array(
		"name" => "Brown",
		"value" => "#9A5D46",
		),
	);

/** @var database connection $connection */
$connection = null;

/**
 * @return bool
 * Checks and creates database connection.
 */
function databaseConnection()
{
	global $connection;
// if connection already exists
	if ($connection != null) {
		return true;
	} else {
		try {
			$connection = new PDO('mysql:host='. DB_HOST .';dbname='. SITE_DB_NAME . ';charset=utf8', SITE_DB_USER, SITE_DB_PASS);
			return true;
		} catch (PDOException $e) {}
	}
// default return
	return false;
}


/**
 * @param $value
 * @param $type
 * @return null|string
 * MusicBee stable and beta release Info
 */
function getVersionInfo($value, $type) {
	global $connection, $lang;
	if (databaseConnection()) {
		try {
			if($type=="byId") {
				$sql = "SELECT * FROM " . SITE_MB_ALL_VERSION_TBL . " WHERE ID_ALLVERSIONS=:value";
			} elseif($type=="byVersion") {
				$sql = "SELECT * FROM " . SITE_MB_ALL_VERSION_TBL . " WHERE version=:value";
			} elseif($type=="byCurrentVersion") {
				$sql = "SELECT * FROM " . SITE_MB_CURRENT_VERSION_TBL . " WHERE ID_VERSION=:value";
			} elseif($type=="byAllReleases") {
				$sql = "SELECT * FROM " . SITE_MB_ALL_VERSION_TBL . " ORDER BY version DESC";
			}
			$statement = $connection->prepare($sql);
			if($type!="byAllReleases") {
				$statement->bindValue(':value', $value);
			}
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) > 0) {
				return $result; //Get the availablity first 1= available, 0=already disabled
			} else {
				if($type=="byId")
					return $lang['AP_NO_RECORD']; //store the error message in the variable
				elseif($type=="byVersion")
					return null; //if we are checking using version we want to send null. since we use count() method for result
			}
		} catch (Exception $e) {
			return "Something went wrong. ".$e; //store the error message in the variable
		}
	}
}

/**
 * @param $string
 * @return string
 * generate slug url
 */
function Slug($string)
{
	return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
}

//unslug text... sort of
function UnslugTxt($string)
{
	return ucfirst(str_replace("-", " ", $string));
}

//adds k,m,b after making a long number short for better presentation
function number_format_suffix($input) {
	$suffixes = array('', 'k', 'm', 'g', 't');
	$suffixIndex = 0;
	
	while(abs($input) >= 1000 && $suffixIndex < sizeof($suffixes))
	{
		$suffixIndex++;
		$input /= 1000;
	}
	
	return (
		$input > 0
                // precision of 3 decimal places
		? floor($input * 10) / 10
		: ceil($input * 10) / 10
		)
	. $suffixes[$suffixIndex];
}
?>