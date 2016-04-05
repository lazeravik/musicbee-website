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
	 * Handles User input validation
	 * @author : AvikB;
	 */
	class Validation
	{

		/**
		 * check if the file/image exists in remote location
		 * @param $url
		 *
		 * @return bool
		 */
		public static function checkRemoteFile($url)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			// don't download content
			curl_setopt($ch, CURLOPT_NOBODY, 1);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			if (curl_exec($ch) !== false) {
				return true;
			} else {
				return false;
			}
		}


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
			global $connection;
			if (databaseConnection()) {
				foreach ($mbversions as $versionId) {
					try {
						$sql = "SELECT ID_ALLVERSIONS FROM " . SITE_MB_ALL_VERSION_TBL . " WHERE ID_ALLVERSIONS = :id";
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

		public static function SanitizeUrl($url)
		{
		    return htmlspecialchars($url, ENT_QUOTES);
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

		/**
		 * @param $rankid
		 *
		 * @return string
		 * @todo improve it by making it more flexible
		 */
		public static function rankName($rankid)
		{
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
	}
