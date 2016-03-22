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
	 * Handles user related stuff
	 * @author : AvikB;
	 */
	class Member
	{

		public function createDashboardAccount($user_id, $user_rankid, $user_name)
		{
			global $connection;
			if ($user_rankid == 1 || $user_rankid == 2) {
				$submitPermission = 1;
			} else {
				$submitPermission = 0;
			}

			if (databaseConnection()) {
				try {
					$sql = "INSERT
								INTO
									".SITE_MEMBER_TBL."
								SET
									ID_MEMBER = :id,
									rank = :permission,
									membername = :name,
									submitPermission = {$submitPermission},
									addon_added = :addon_added";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':id', $user_id);
					$statement->bindValue(':permission', $user_rankid);
					$statement->bindValue(':name', $user_name);
					$statement->bindValue(':addon_added', 0);
					$statement->execute();
				} catch (Exception $e) {
					return false;
				}

				//check if user's dashboard account creation is successful or not
				if($this->memberInfo($user_id) != null) {
					return true;
				} else {
					return false;
				}
			}
		}

		/**
		 * @param $user_id
		 *
		 * @return null
		 * Gets the member rank, if the ID_MEMBER is known. ID_MEMBER can be easily obtained if the user is logged into the forum
		 */
		public function memberInfo($user_id)
		{
			global $connection;
			if (databaseConnection()) {
				try {
					$sql = "SELECT * FROM " . SITE_MEMBER_TBL . " WHERE ID_MEMBER = :user_id";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':user_id', $user_id);
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					if (count($result) > 0) {
						return $result[0];
					} else {
						return null;
					}
				} catch (Exception $e) {

				}
			}

			return null;
		}

		/**
		 * @param $rankid
		 *
		 * @return string
		 * @todo improve it by using array with foreach switch, it will make it more feature rich and flexible
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
