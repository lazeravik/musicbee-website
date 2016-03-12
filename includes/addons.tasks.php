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
* @author: AvikB
* @version: 1.0
* 
*/
$no_guests = true; //kick off the guests
$json_response = true; //if the user is not logged in then send the json error instead of redirecting
require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';
require_once $siteRoot.'classes/Addon.php';
require_once $siteRoot.'includes/languages/en-us.php'; //gets text descriptions for errors and success message

if (ctype_digit($_POST['id'])) {
	$addon = new Addon();
	$addon_already_liked = $addon->is_rated($_POST['id'],$user_info['id']);
	if ($addon_already_liked == true) {
		#if already liked then unlike it or simply remove the record
		if($addon->rate($_POST['id'],$user_info['id'],"unlike")) {
			exit('{"status": "1", "data": "'.$lang['266'].'", "callback_function": "remove_rating"}');
		}
	} elseif ($addon_already_liked == false) {
		#if the addon still isn't liked then like it
		if($addon->rate($_POST['id'],$user_info['id'],"like")) {
			exit('{"status": "1", "data": "'.$lang['265'].'", "callback_function": "add_rating"}');
		}
	} else {
		die('{"status": "0", "data": "'.$lang['267'].'"}');
	}
	
} else {
	die('{"status": "0", "data": "'.$lang['264'].'"}');
}
