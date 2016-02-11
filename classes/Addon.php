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
* Handles ADDON DASHBOARD(musicbee addon center) related stuff
* @author : AvikB;
*/

class Addon
{
//Gets the member rank, if the ID_MEMBER is known. ID_MEMBER can be easily obtained if the user is logged into the forum
public function memberInfo($ID)
{
	global $connection;
	if (databaseConnection()) {
		try {
			$sql = "SELECT * FROM ".SITE_MEMBER_TBL." WHERE ID_MEMBER = :id";
			$statement = $connection->prepare($sql);
			$statement->bindValue(':id', $ID);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) == 1)
			{
				return $result;
			}else{
				return null;
			}
		} catch (Exception $e) {

		}
	}
}

//gets all the addons from this member
public function getAddonListbyMember($id,$limit)
{
	global $connection;
	if (databaseConnection()) {
		try {
			$sql = "SELECT * FROM ".SITE_ADDON." WHERE ID_AUTHOR = :id ORDER BY ID_ADDON DESC LIMIT ".$limit;
			$statement = $connection->prepare($sql);
			$statement->bindValue(':id', $id);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) > 0)
			{
				return $result;
			}else{
				return null;
			}
		} catch (Exception $e) {

		}
	}
}


public function getUnApprovedAddonsbyMember($id,$limit,$stat)
{
	global $connection;
	if (databaseConnection()) {
		try {
			$sql = "SELECT * FROM ".SITE_ADDON." WHERE ID_AUTHOR = :id AND status = ".$stat." ORDER BY ID_ADDON DESC LIMIT ".$limit;
			$statement = $connection->prepare($sql);
			$statement->bindValue(':id', $id);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) > 0)
			{
				return $result;
			}else{
				return null;
			}
		} catch (Exception $e) {

		}
	}
}

/* We also want to get rank name by rank id. Highest rank is 1, and lowest is 10*/
public function rankName($rankid)
{
	$rankname;
	switch ($rankid) {
		case 1:
		$rankname = "Admin";
		break;
		case 2:
		$rankname = "Mod";
		break;
		case 5:
		$rankname = "Elite";
		break;
		case 10:
		$rankname = "Newbie";
		break;
		default:
		$rankname = "Unknown";
		break;
	}
	return $rankname;
}

public function getStatus($id)
{
	switch ($id) {
		case '0':
			return "Waiting for Approval";
			break;
		case '1':
			return "Approved";
			break;
		case '2':
			return "Rejected";
			break;
		case '3':
			return "Deleted";
			break;
		default:
			return "Unknown";
			break;
	}
}

public function checkMbVersions($val)
{
	global $connection;
	$ver = explode(",", $val); //create an array of supported musicbee versions
	if (databaseConnection()) {
		foreach ($ver as $versionId) {
			try {
				$sql = "SELECT ID_ALLVERSIONS FROM ".SITE_MB_ALL_VERSION_TBL." WHERE ID_ALLVERSIONS = :id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':id', $versionId);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if (count($result) != 1)
				{
					return false;
				}
			} catch (Exception $e) {
				return false;
			}
		}
		return true;
	}
}

public function getMbVersions($val)
{
	global $connection;
	$ver = explode(",", $val); //create an array of supported musicbee versions
	if (databaseConnection()) {
		foreach ($ver as $versionId) {
			try {
				$sql = "SELECT * FROM ".SITE_MB_ALL_VERSION_TBL." WHERE ID_ALLVERSIONS = :id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':id', $versionId);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if (count($result) > 0)
				{
					return $result;
				}
			} catch (Exception $e) {
				return null;
			}
		}
		return null;
	}
}


public function is_rated($addon_id, $user_id)
{
	global $connection;
	if (databaseConnection()) {
		try {
			$sql = "SELECT * FROM ".SITE_ADDON_LIKE." WHERE ID_MEMBER = :user_id AND ID_ADDON = :addon_id";
			$statement = $connection->prepare($sql);
			$statement->bindValue(':user_id', $user_id);
			$statement->bindValue(':addon_id', $addon_id);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) > 0)
			{
				return true;
			}else{
				return false;
			}
		} catch (Exception $e) {

		}
	}
}


public function rate($addon_id, $user_id, $rate_val)
{
	global $connection;
	if (databaseConnection()) {
		try {
			if ($rate_val == "like") {
				$sql = "INSERT INTO ".SITE_ADDON_LIKE." SET ID_MEMBER = :user_id, ID_ADDON = :addon_id";
			} elseif ($rate_val == "unlike") {
				$sql = "DELETE FROM ".SITE_ADDON_LIKE." WHERE ID_MEMBER = :user_id AND ID_ADDON = :addon_id";
			}
			$statement = $connection->prepare($sql);
			$statement->bindValue(':user_id', $user_id);
			$statement->bindValue(':addon_id', $addon_id);
			$statement->execute();

			return true;
		} catch (Exception $e) {

		}
	}
}


public function getRating($addon_id, $raw = false)
{
	global $connection;
	if (databaseConnection()) {
		try {
			$sql = "SELECT * FROM ".SITE_ADDON_LIKE." WHERE ID_ADDON = :id";
			$statement = $connection->prepare($sql);
			$statement->bindValue(':id', $addon_id);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) >= 0)
			{
				return count($result);
			}
		} catch (Exception $e) {
			return null;
		}
	}
}

public function getAddonInfo($id)
{
	global $connection;
	if (databaseConnection()) {
		try {
			if ($id==null){
				$sql = "SELECT ID_ADDON, ID_AUTHOR, COLOR_ID, addon_title, addon_type, thumbnail, is_beta, status FROM ".SITE_ADDON;
				$statement = $connection->prepare($sql);
			} else {
				$sql = "SELECT * FROM ".SITE_ADDON." WHERE ID_ADDON = :id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':id', $id);
			}
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) > 0)
			{
				return $result;
			}
		} catch (Exception $e) {
			return $e;
		}
	}
}


public function getAddonFiltered($cat,$order=null)
{
	global $connection;
	if (databaseConnection()) {
		try {
			if ($cat==null){
				$sql = "SELECT ID_ADDON, ID_AUTHOR, COLOR_ID, addon_title, addon_type, thumbnail, is_beta, status FROM ".SITE_ADDON." ORDER BY ID_ADDON DESC";
				$statement = $connection->prepare($sql);
			} else {
				$sql = "SELECT ID_ADDON, ID_AUTHOR, COLOR_ID, addon_title, addon_type, thumbnail, is_beta, status FROM ".SITE_ADDON." WHERE addon_type = :cat ORDER BY ID_ADDON DESC";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':cat', $cat);
			}
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) > 0)
			{
				return $result;
			}
		} catch (Exception $e) {
			return $e;
		}
	}
}
}
?>