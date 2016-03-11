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

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
require_once $siteRoot . 'classes/Addon.php';
require_once $siteRoot . 'classes/Format.php';
$addon = new Addon(); 


if (isset($_GET['id'])) {
	if (ctype_digit($_GET['id'])) {
		$data = $addon->getAddonInfo($_GET['id'])[0];
		//if no addon data is found on the database then it is likely to be deleted, throw a 404 error
		if (null == $data) {
			header("HTTP/1.0 404 Not Found");
			include_once $status['404'];
			exit();
		}

		//checks the url parameter and try to match the addon title, if the addon id matches but not the title, then do a 301 redirect
		if (count($params) <= 4 || $params[3] != Slug($data['addon_title'])) {
			header("Location: " . $siteUrl . "addons/" . $data['ID_ADDON'] . "/" . Slug($data['addon_title']) . "", 301);
		}

		//Since the addon is found and everything is ok, create screenshot link array
        $screenshots = explode(",", $data['image_links']);

        //Gets the addon author info
        $author = $addon->memberInfo($data['ID_AUTHOR'])[0];

        //Create an array of supported musicbee version for this array
        foreach (explode(",", $data['supported_mbversion']) as $mbVer) {
        	$mbVerArray[] = $addon->getMbVersions($mbVer)[0]['appname'];
        }

        //get addon specific info like likes, title, description etc.
        $meta_description = "Download MusicBee skins, plugins, theater mode, visualizer and more..";
        $addon_type = UnslugTxt($data['addon_type']);
        $from_author = $addon->getAddonListbyMember($data['ID_AUTHOR'], 4);
        $addon_like = $addon->getRating($data['ID_ADDON']);
        $addon_already_liked = $addon->is_rated($data['ID_ADDON'],$user_info['id']);

        include_once $siteRoot . 'includes/addons.selected.template.php';
    	exit();

    } elseif (array_key_exists($_GET['id'], $main_menu['add-ons']['sub_menu'])) {

    	$meta_description = "blah";
    	$data['type'] = $main_menu['add-ons']['sub_menu'][$_GET['id']]['title'];
    	$addon_type = Slug($data['type']);

    	$addon_total = $addon->getAddonCount($addon_type);
    	$page_total = ceil($addon_total/$addon_view_range);
    	//Addon Pagination function!
    	//remove the ? sign from the string and convert the url paramenter into an array
		parse_str(str_replace("?", "", $params[3]),$url_params); 
    	if (isset($url_params['p'])) {
    		$data['addon'] = $addon->getAddonFiltered($addon_type, null, $url_params['p']);
    	} else {
    		$data['addon'] = $addon->getAddonFiltered($addon_type);
    	}

    	include_once $siteRoot . 'includes/addons.view.template.php';
    	exit();

    } elseif ($_GET['id'] == "all") {
    	$meta_description = "blah";
    	$data['type'] = "All";
    	$addon_type = Slug($data['type']);

    	$addon_total = $addon->getAddonCount();
    	$page_total = ceil($addon_total/$addon_view_range);
    	//Addon Pagination function!
    	//remove the ? sign from the string and convert the url paramenter into an array
		parse_str(str_replace("?", "", $params[3]),$url_params); 
    	if (isset($url_params['p'])) {
    		$data['addon'] = $addon->getAddonFiltered(null, null, $url_params['p']);
    	} else {
    		$data['addon'] = $addon->getAddonFiltered(null);
    	}

    	include_once $siteRoot . 'includes/addons.view.template.php';
    	exit();
    } else {
    	header("HTTP/1.0 404 Not Found");
    	include_once $status['404'];
    	exit();
    }
} else {
	header("Location: " . $link['addon']['home'] . "/all");
	exit();
}
?>
