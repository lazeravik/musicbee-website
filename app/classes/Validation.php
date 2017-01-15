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
 * Handles User input validation
 * @author : AvikB;
 */
class Validation
{
	/**
	 * Check if the string has certain amount of character or not
	 * @param string $input
	 * @param int    $limit
	 *
	 * @return bool
	 */
	public static function charLimit($input, $limit)
	{
		if (strlen($input) <= $limit) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * array limit validation, used for user inputs that use commas for storing multiple values
	 * @param string $input string with commas
	 * @param int $limit
	 *
	 * @return bool
	 */
	public static function arrayLimit($input, $limit)
	{
		$inputArray = explode(",", $input);
		if (count($inputArray) <= $limit) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Checks if the musicbee version exists or not
	 * @param array $mbversions
	 *
	 * @return bool
	 */
	public static function validateMusicBeeVersions($mbversions)
	{
		global $connection, $db_info;
		if (databaseConnection()) {
			foreach ($mbversions as $versionId) {
				try {
					$sql = "SELECT ID_ALLVERSIONS FROM {$db_info['mb_all']} WHERE ID_ALLVERSIONS = :id";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':id', $versionId);
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					if (count($result) != 1) {
						return false;
					}
				} catch (Exception $e) {
					return false;
				}
			}
			return true;
		}
	}

	public static function validateMusicBeeVersion($mbversions)
	{
		global $connection, $db_info;
		if (databaseConnection()) {
			try {
				$sql = "SELECT ID_ALLVERSIONS FROM {$db_info['mb_all']} WHERE ID_ALLVERSIONS = :id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':id', $mbversions);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if (count($result) != 1) {
					return false;
				}
			} catch (Exception $e) {
				return false;
			}
			return true;
		}
	}


	public static function getStatus($id)
	{
		global $lang;

		switch ($id) {
			case '0':
				return array('icon' => $lang['addon_status_icon_1'], 'text'=> $lang['addon_status_1']);
				break;
			case '1':
				return array('icon' => $lang['addon_status_icon_2'], 'text'=> $lang['addon_status_2']);
				break;
			case '2':
				return array('icon' => $lang['addon_status_icon_3'], 'text'=> $lang['addon_status_3']);
				break;
			case '3':
				return array('icon' => $lang['addon_status_icon_4'], 'text'=> $lang['addon_status_4']);
				break;
			default:
				return array('icon' => $lang['addon_status_icon_5'], 'text'=> $lang['addon_status_5']);
				break;
		}
	}


}
