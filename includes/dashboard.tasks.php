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
require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';

require_once $siteRoot.'classes/Dashboard.php';
require_once $siteRoot.'classes/Validation.php';
require_once $siteRoot.'includes/languages/en-us.php'; //gets text descriptions for errors and success message
require_once $siteRoot.'includes/html-purifier/HTMLPurifier.auto.php'; //load html purifier
include_once $siteRoot.'includes/parsedown/Parsedown.php';


//enable and disable downloads
if (isset($_POST['submit'])) {
	global $connection, $endMsg;
	if (isset($_POST['type'])
		&& isset($_POST['title']) 
		&& isset($_POST['description']) 
		&& isset($_POST['mbSupportedVer']) 
		&& isset($_POST['tag']) 
		&& isset($_POST['dlink'])
		&& isset($_POST['thumb']) 
		&& isset($_POST['screenshot_links'])) {
		if(count(getAddonInfo($_POST['title'], "title", null)) > 0){
			die('{"status": "0", "data": "'.$lang['201'].'"}');
		} else {
			$dashboard = new Dashboard();
			if (!array_key_exists($_POST['type'], $main_menu['add-ons']['sub_menu'])) {
				die('{"status": "0", "data": "'.$lang['216'].'"}');
			}
			//validate user inputs
			if (!$dashboard->checkMbVersions($_POST['mbSupportedVer'])){
				die('{"status": "0", "data": "'.$lang['202'].'"}');
			}
			if (!Validation::charLimit($_POST['description'], 600)){
				die('{"status": "0", "data": "'.$lang['203'].'"}');
			}
			if (!Validation::arrayLimit($_POST['tag'], 10)) {
				die('{"status": "0", "data": "'.$lang['204'].'"}');
			}
			if (isset($_POST['color'])) {
				if (!array_key_exists($_POST['color'], $color_codes)) {
					die('{"status": "0", "data": "'.$lang['223'].'"}');
				}
			}
			if (isset($_POST['readme'])){
				if (!Validation::charLimit($_POST['readme'], 5000))
					die('{"status": "0", "data": "'.$lang['205'].'"}');
			}
			//die, if the user alreay submitted more than X numbers of addon that needed aproval!
			//This will prevent the floodgate
			if (count(getAddonInfo(0, "status", $context['user']['id'])) > MAX_SUBMIT_WO_APPROVAL) {
				die('{"status": "0", "data": "'.$lang['206'].'"}');
			}

			$readme = (isset($_POST['readme']))? $_POST['readme'] : "" ;
			//load parsedown markup to html converter
			$Parsedown = new Parsedown();
			$readme_raw = $Parsedown->text($readme);
			//load and use html purifier for the readme notes.
			$config = HTMLPurifier_Config::createDefault();
			$config->set('HTML.Allowed', 'code,*[class],*[lang-rel],p,pre,table,thead,tbody,td,tr,th,h2,h1,h3,h4,h5,span,ul,li,ol,strong,blockquote,em,a[href|title],img[src]');
			$def = $config->getHTMLDefinition(true);
			$def->addAttribute('code', 'lang-rel', 'Text');
			$purifier = new HTMLPurifier($config);
			$readme_html = $purifier->purify($readme_raw); //purify the readme note html

			//Phew.... all validations complete, now SUBMIT THE ADDON!
			if ($dashboard->submit($_SESSION['memberinfo']['rank_raw'],$context['user']['id'],$readme_html,"submit")) {
				echo '{"status": "1", "data": "'.$lang['207'].'"}';
			}
		}
	}
} elseif (isset($_POST['modify_type'])) {
	if ($_POST['modify_type']=="delete") {
		$dashboard = new Dashboard();
		if ($dashboard->verifyAuthor($user_info['id'], $_POST['record_id'])) {
			if ($dashboard->deleteAddon($_POST['record_id'])) {
				exit('
				     {
				     	"status": "1", 
				     	"data": "'.$lang['220'].'", 
				     	"callback_function": "remove_addon_record"
				     }
				     ');
			} else {
				//:S addon deletation failed! and we have no clue.... bummer
				die('{"status": "0", "data": "'.$lang['221'].'"}');
			}
		} else {
			//throw error if the author is different than the submitter itself
			die('{"status": "0", "data": "'.$lang['219'].'"}');
		}
	} elseif ($_POST['modify_type']=="update") {
		$dashboard = new Dashboard();
		if (!array_key_exists($_POST['type'], $main_menu['add-ons']['sub_menu'])) {
			die('{"status": "0", "data": "'.$lang['216'].'"}');
		}
		//validate user inputs
		if (!$dashboard->checkMbVersions($_POST['mbSupportedVer'])){
			die('{"status": "0", "data": "'.$lang['202'].'"}');
		}
		if (!Validation::charLimit($_POST['description'], 600)){
			die('{"status": "0", "data": "'.$lang['203'].'"}');
		}
		if (!Validation::arrayLimit($_POST['tag'], 10)) {
			die('{"status": "0", "data": "'.$lang['204'].'"}');
		}
		if (isset($_POST['color'])) {
			if (!array_key_exists($_POST['color'], $color_codes)) {
				die('{"status": "0", "data": "'.$lang['223'].'"}');
			}
		}
		if (isset($_POST['readme'])){
			if (!Validation::charLimit($_POST['readme'], 5000)){
				die('{"status": "0", "data": "'.$lang['205'].'"}');
			}
		}
		//verify if the author can modify it.
		if (!$dashboard->verifyAuthor($user_info['id'], $_POST['record_id'])) {
			die('{"status": "0", "data": "'.$lang['219'].'"}');
		}

		$readme = (isset($_POST['readme']))? $_POST['readme'] : "" ;
		//load parsedown markup to html converter
		$Parsedown = new Parsedown();
		$readme_raw = $Parsedown->text($readme);
		//load and use html purifier for the readme notes.
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.Allowed', 'code,*[class],*[lang-rel],p,pre,table,thead,tbody,td,tr,th,h2,h1,h3,h4,h5,span,ul,li,ol,strong,blockquote,em,hr,a[href|title],img[src]');
		$def = $config->getHTMLDefinition(true);
		$def->addAttribute('code', 'lang-rel', 'Text');
		$purifier = new HTMLPurifier($config);
		$readme_html = $purifier->purify($readme_raw); //purify the readme note html

		//Phew.... all validations complete, now SUBMIT THE ADDON!
		if ($dashboard->submit($_SESSION['memberinfo']['rank_raw'],$context['user']['id'],$readme_html,"update")) {
			echo '{"status": "1", "data": "'.$lang['224'].'"}';
		}
	} else {
		//$_POST['modify_type'] contain unknown value! DIEEEEEE!!!! ^_^
		die('{"status": "0", "data": "'.$lang['222'].'"}');
	}
} 
/* Get the download availablity of the current and beta version*/
function getAddonInfo($value, $reqType, $authorId) {
	global $connection, $endMsg, $lang;
	if (databaseConnection()) {
		try {
			if ($reqType=="title")
				$sql = "SELECT * FROM ".SITE_ADDON." WHERE addon_title = :value";
			elseif ($reqType=="status")
				$sql = "SELECT * FROM ".SITE_ADDON." WHERE status = :value AND ID_AUTHOR = :authorId";
			$statement = $connection->prepare($sql);
			$statement->bindValue(':value', $value);
			if ($reqType=="status")
				$statement->bindValue(':authorId', $authorId);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) > 0) {
				return $result; 
			} else {
				$endMsg = $lang['AP_NO_RECORD']; //store the error message in the variable
			}
		} catch (Exception $e) {
			$endMsg = "Something went wrong. ".$e; //store the error message in the variable
		}
	}
}
/* Set the download availablity of the current and beta version*/
function setAvailablity($isAvailable, $id_version) {
	global $connection, $endMsg, $lang;
	$isAvailable = ($isAvailable == 1)? "0":"1";

	if (databaseConnection()) {
		try {
			$sql =  "UPDATE ".SITE_MB_CURRENT_VERSION_TBL." SET available = ".$isAvailable." WHERE ID_VERSION=".$id_version;
			$statement = $connection->prepare($sql);
			$statement->execute();
		} catch (Exception $e) {
			$endMsg = "Something went wrong. ".$e; //store the error message in the variable
			return false;
		}
		return true;
	}
}

/*Get all version to show it on the "all musicbee release" page*/
function getAllVersion() {
	global $connection, $lang;
	if (databaseConnection()) {
		try {
			$sql = "SELECT * FROM ".SITE_MB_ALL_VERSION_TBL." ORDER BY version DESC LIMIT 10000 ";
			$statement = $connection->prepare($sql);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) > 0) {
				return $result; //Get the availablity first 1= available, 0=already disabled
			} else {
				return $lang['AP_NO_RECORD']; //store the error message in the variable
			}
		} catch (Exception $e) {
			return "Something went wrong. ".$e; //store the error message in the variable
		}
	}
}
