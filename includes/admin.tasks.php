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
 * @author : AvikB
 * @version: 1.0
 *
 */

$admin_only = true; //only for admins
require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';
require_once $link['root'].'classes/Manager.php'; // Save and update the data on the database
require_once $link['root'].'includes/languages/en-us.php'; //gets text descriptions for errors and success message
require_once $link['root'].'includes/html-purifier/HTMLPurifier.auto.php'; //load html purifier
include_once $link['root'].'includes/parsedown/Parsedown.php';

/**
 * enable and disable downloads
 */
if(isset($_POST['change_id'])) {
	if($_POST['change_id'] == "stable_download_disable") {
		$id_version = 0;
		if(empty($endMsg)) {
			//since the download is enabled we want to disable it.
			if(setAvailablity(getAvavilability($id_version), $id_version)) {
				if(getAvavilability($id_version) == 1) {
					echo '{"status": "1", "data": "'.$lang['AP_SR_ENABLED'].'", "callback_function": "reload_view"}';
				} else {
					echo '{"status": "2", "data": "'.$lang['AP_SR_DISABLED'].'", "callback_function": "reload_view"}';
				}
			} elseif(!empty($endMsg)) {
				echo '{"status": "0", "data": "'.$endMsg.'"}';
			}
		}
	} elseif($_POST['change_id'] == "beta_download_disable") {
		$id_version = 1;
		if(empty($endMsg)) {
			//since the download is enabled we want to disable it.
			if(setAvailablity(getAvavilability($id_version), $id_version)) {
				if(getAvavilability($id_version) == 1) {
					echo '{"status": "1", "data": "'.$lang['AP_BR_ENABLED'].'", "callback_function": "reload_view"}';
				} else {
					echo '{"status": "2", "data": "'.$lang['AP_BR_DISABLED'].'", "callback_function": "reload_view"}';
				}
			} elseif(!empty($endMsg)) {
				echo '{"status": "0", "data": "'.$endMsg.'"}';
			}
		}
	}
}

/**
 * save or update records
 */

elseif(isset($_POST['save'])) {
	$manager = new Manager(); //create an instance of the MANAGER class
	$note_html = "";
	if($_POST['save'] == "stable") {
		//load parsedown markup to html converter
		$Parsedown = new Parsedown();
		$note_html_raw = $Parsedown->text($_POST['note']);
		$note_html = Format::htmlSafeOutput($note_html_raw);

		/**
		 * If Download link 1 and portable link is assigned while also this entry is not new!
		 * then we assume the admin is updating the CURRENT ACTIVE version and we want to update ALL the fields
		 * incl download link fields, other wise we ONLY want to update other fields than download links.
		 */
		if(isset($_POST['ilink1'], $_POST['plink1']) && !isset($isnew)) {
			$_POST['isCurrent'] = "true";
		}
	}
	if(count(getVersionInfo($_POST['ver'], "byVersion")) == 0 || isset($_POST['id_allversion'])) {
		if($manager->saveArchieveData($note_html)) {
			if($_POST['save'] == "stable") {
				echo '{"status": "1", "data": "'.$lang['AP_SR_SAVED_SUCCESS'].'", "callback_function": "view_list"}';
			} elseif($_POST['save'] == "beta") {
				echo '{"status": "1", "data": "'.$lang['AP_BR_SAVED_SUCCESS'].'"}';
			}

		} else {
			echo '{"status": "0", "data": "'.$manager->errorMessage.'"}';
		}
	} else {
		echo '{"status": "0", "data": "'.$lang['AP_SR_RECORD_EXIST'].'"}';
	}

}

/**
 * Delete records. There is no serverside checking if the deleting version is the current one.
 * The client side delete button should be greyed out. BUT since the user HAS to be an admin we are not too strict on checking any fake delete request.
 * THOUGH maybe in future there will be a check
 */

elseif(isset($_POST['modify_type'])) {
	$manager = new Manager(); //create an instance of the MANAGER class
	if($_POST['modify_type'] == "delete") {
		if($manager->deleteRecord($_POST['record_id'])) {
			echo '{"status": "1", "data": "'.$lang['AP_RECORD_DELETED'].'", "callback_function": "record_deleted"}';
		} else {
			echo '{"status": "0", "data": "'.$manager->errorMessage.'"}';
		}
	}
}

/* Get the download availablity of the current and beta version*/
function getAvavilability($id_version) {
	global $connection, $endMsg, $lang, $db_info;

	if(databaseConnection()) {
		try {
			$sql = "SELECT available FROM {$db_info['mb_current']} WHERE ID_VERSION= $id_version";
			$statement = $connection->prepare($sql);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if(count($result) > 0) {
				return $result[0]['available']; //Get the availablity first 1= available, 0=already disabled
			} else {
				$endMsg = $lang['AP_NO_RECORD']; //store the error message in the variable
			}
		} catch(Exception $e) {
			$endMsg = "Something went wrong. ".$e; //store the error message in the variable
		}
	}
}

/* Set the download availablity of the current and beta version*/
function setAvailablity($isAvailable, $id_version) {
	global $connection, $endMsg, $db_info;

	$isAvailable = ($isAvailable == 1) ? "0" : "1";
	if(databaseConnection()) {
		try {
			$sql = "UPDATE {$db_info['mb_current']} SET available = {$isAvailable} WHERE ID_VERSION= {$id_version}";
			$statement = $connection->prepare($sql);
			$statement->execute();
		} catch(Exception $e) {
			$endMsg = "Something went wrong. ".$e; //store the error message in the variable
			return false;
		}

		return true;
	}
}

/*Get all version to show it on the "all musicbee release" page*/
function getAllVersion($offset = 0, $range = 40) {
	global $connection, $lang, $db_info;

	if(databaseConnection()) {
		try {
			$sql = "SELECT * FROM {$db_info['mb_all']} ORDER BY version DESC LIMIT {$offset} , {$range}";
			$statement = $connection->prepare($sql);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if(count($result) > 0) {
				return $result; //Get the availablity first 1= available, 0=already disabled
			} else {
				return $lang['AP_NO_RECORD']; //store the error message in the variable
			}
		} catch(Exception $e) {}
	}
}

function getAllVersionCount() {
	global $connection, $db_info;

	if(databaseConnection()) {
		try {
			$sql = "SELECT COUNT(*) as total_release FROM {$db_info['mb_all']}";
			$statement = $connection->prepare($sql);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);

			return $result[0]['total_release'];
		} catch(Exception $e) {}
	}
}