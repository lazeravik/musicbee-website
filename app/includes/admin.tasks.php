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

/**
 * @author : AvikB
 * @version: 1.0
 *
 */

$admin_only = true; //only for admins
require_once $_SERVER['DOCUMENT_ROOT'].'/app/functions.php';
require_once $link['root'].'classes/Manager.php'; // Save and update the data on the database
include_once $link['root'].'includes/parsedown/Parsedown.php';


/**
 * enable and disable downloads
 */
if(isset($_POST['change_id']))
{
	if($_POST['change_id'] == "stable_download_disable")
	{
		$id_version = 0;
		if(empty($endMsg))
		{
			//since the download is enabled we want to disable it.
			if(setAvailablity(getAvavilability($id_version), $id_version))
			{
				if(getAvavilability($id_version) == 1)
				{
					echo '{"status": "1", "data": "'.$lang['stable_release_enabled'].'", "callback_function": "reload_view"}';
				}
				else
				{
					echo '{"status": "2", "data": "'.$lang['stable_release_disabled'].'", "callback_function": "reload_view"}';
				}
			}
			elseif(!empty($endMsg))
			{
				die('{"status": "0", "data": "'.$endMsg.'"}');
			}
		}
	}
	elseif($_POST['change_id'] == "beta_download_disable")
	{
		$id_version = 1;
		if(empty($endMsg))
		{
			//since the download is enabled we want to disable it.
			if(setAvailablity(getAvavilability($id_version), $id_version))
			{
				if(getAvavilability($id_version) == 1)
				{
					echo '{"status": "1", "data": "'.$lang['beta_release_enabled'].'", "callback_function": "reload_view"}';
				}
				else
				{
					echo '{"status": "2", "data": "'.$lang['beta_release_disbled'].'", "callback_function": "reload_view"}';
				}
			}
			elseif(!empty($endMsg))
			{
				die('{"status": "0", "data": "'.$endMsg.'"}');
			}
		}
	}
}

/**
 * save or update records
 */

elseif(isset($_POST['save'])) {
	$manager = new Manager(); //create an instance of the MANAGER class

	if($_POST['save'] == 'patch')
	{
		if($manager->savePatch())
		{
			exit('{"status": "1", "data": "'.$lang['patch_saved'].'", "callback_function": "go_to"}');
		}
		else
		{
			die('{"status": "0", "data": "'.$manager->errorMessage.'"}');
		}
	}
	else
	{
		$note_html = "";
		if($_POST['save'] == "stable")
		{
			//load parsedown markup to html converter
			$Parsedown = new Parsedown();
			$note_html_raw = $Parsedown->text($_POST['note']);
			$note_html = Format::htmlSafeOutput($note_html_raw);

			/**
			 * If Download link 1 and portable link is assigned while also this entry is not new!
			 * then we assume the admin is updating the CURRENT ACTIVE version and we want to update ALL the fields
			 * incl download link fields, other wise we ONLY want to update other fields than download links.
			 */
			if(isset($_POST['ilink1'], $_POST['plink1']) && !isset($isnew))
			{
				$_POST['isCurrent'] = "true";
			}
		}
		if(count(getVersionInfo($_POST['ver'], "byVersion")) == 0 || isset($_POST['id_allversion']))
		{
			if($manager->saveArchieveData($note_html))
			{
				if($_POST['save'] == "stable")
				{
					exit('{"status": "1", "data": "'.$lang['stable_release_saved_updated'].'", "callback_function": "view_list"}');
				}
				elseif($_POST['save'] == "beta")
				{
					exit('{"status": "1", "data": "'.$lang['beta_release_saved'].'"}');
				}

			}
			else
			{
				die('{"status": "0", "data": "'.$manager->errorMessage.'"}');
			}
		} else {
			die('{"status": "0", "data": "'.$lang['version_exists'].'"}');
		}
	}



}
elseif(isset($_POST['modify_type']))
{
	$manager = new Manager(); //create an instance of the MANAGER class
	if($_POST['modify_type'] == "delete")
	{
		//Check if the release is in use or not. If it is current release ask the admin to submit new release first beofre deleting it
		if($manager->compareCurrentRelease($_POST['record_id'])){
			die('{"status": "0", "data": "'.$lang['err_current_release_delete']."<br/>".$manager->errorMessage.'"}');
		}

		if($manager->deleteRecord($_POST['record_id'])) {
			exit('{"status": "1", "data": "'.$lang['deleted'].'", "callback_function": "record_deleted"}');
		} else {
			die('{"status": "0", "data": "'.$manager->errorMessage.'"}');
		}
	}
}
elseif(isset($_POST['delete']))
{
	if($_POST['delete'] == 'patch')
	{
		$manager = new Manager();
		if($manager->deletePatch()){
			exit('{"status": "1", "data": "'.$lang['deleted'].'", "callback_function": "refresh"}');
		} else {
			die('{"status": "0", "data": "'.$manager->errorMessage.'"}');
		}
	}
}

/* Get the download availablity of the current and beta version*/
function getAvavilability($id_version) {
	global $connection, $endMsg, $lang, $db_info;

	if(databaseConnection()) {
		try {
			$sql = "SELECT available FROM {$db_info['mb_current']} WHERE ID_VERSION= :id_ver";
			$statement = $connection->prepare($sql);
			$statement->bindValue(':id_ver',$id_version);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if(count($result) > 0) {
				return $result[0]['available']; //Get the availablity first 1= available, 0=already disabled
			} else {
				$endMsg = $lang['no_record']; //store the error message in the variable
				return false;
			}
		} catch(Exception $e) {
			$endMsg = "Something went wrong. ".$e; //store the error message in the variable
			return false;
		}
	}
	return false;
}

/* Set the download availablity of the current and beta version*/
function setAvailablity($isAvailable, $id_version) {
	global $connection, $endMsg, $db_info;

	$isAvailable = ($isAvailable == 1) ? "0" : "1";
	if(databaseConnection()) {
		try {
			$sql = "UPDATE {$db_info['mb_current']} SET available = :availability WHERE ID_VERSION= :id_ver";
			$statement = $connection->prepare($sql);
			$statement->bindValue(':id_ver',$id_version);
			$statement->bindValue(':availability',$isAvailable);
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
				return $lang['no_record']; //store the error message in the variable
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
