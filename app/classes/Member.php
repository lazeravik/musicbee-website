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
 * Handles user related stuff
 * @author : AvikB;
 */
class Member
{

	public function __construct()
	{
		global $mb, $setting;

		if(!$mb['user']['is_guest'])
		{
			//permission level 1 means the member is admin
			//permission level 2 means the member is mod
			//permission level 5 means the member is elite
			//permission level 10 means the member is noob
			if($mb['user']['is_admin'])
			{
				$permission = 1;
			}
			elseif($mb['user']['can_mod'])
			{
				$permission = 2;
			}
			else
			{
				$permission = 10;
			}

			if($this->memberInfo($mb['user']['id'])['rank'] == null) {
				//Create an Addon dashboard account for the user
				if($this->createDashboardAccount($mb['user']['id'], $permission, $mb['user']['name'])) {
					$userinfo = $this->memberInfo($mb['user']['id']);
				} else {
					//@todo: put some error feedback
				}
			} else {
				//check if forum username updated,
				$userinfo = $this->memberInfo($mb['user']['id']);

				//Update the website name if the forum name is updated
				if($userinfo['membername'] != $mb['user']['username']) {
					if($this->updateUsername($mb['user']['id'], $mb['user']['username'])) {
						$userinfo = $this->memberInfo($mb['user']['id']);
					}
				}
			}

			//Get total approved addon count of the user
			$totalApprovedAddon = $this->getAddonCountByUser($mb['user']['id']);

			//If the user is not an admin or mod but already reached elite requirement, make the user elite
			if($totalApprovedAddon >= $setting['eliteRequirement'] && !$mb['user']['can_mod']) {
				$permission = 5;
			}

			//If permission is not equal to rank then update the data and keep it in sync with the forum
			if(isset($userinfo['rank'])) {
				if ($userinfo['rank'] != $permission) {
					$this->updateUserRank($mb['user']['id'], $permission);
				}
			}

			//Set the user ranks and permissions once we get it from database
			$mb['user']['is_elite'] = ($userinfo['rank'] == 5) ? true : false;
			$mb['user']['is_newbie'] = ($userinfo['rank'] == 10) ? true : false;
			$mb['user']['rank_name'] = $this::rankName($userinfo['rank']);
			$mb['user']['total_approved_addon'] = $totalApprovedAddon;
			$mb['user']['need_approval'] = ($mb['user']['total_approved_addon'] >= $setting['selfApprovalRequirement'] || $mb['user']['can_mod']) ? false : true;

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

	/**
	 * Creates a dashboard account using forum account info
	 *
	 * @param $user_id
	 * @param $user_rankid
	 * @param $user_name
	 *
	 * @return bool
	 */
	public function createDashboardAccount($user_id, $user_rankid, $user_name) {
		global $connection, $db_info;

		if(databaseConnection()) {
			try {
				$sql = "INSERT
								INTO
									{$db_info['member_tbl']}
								SET
									ID_MEMBER = :id,
									rank = :permission,
									membername = :name";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':id', $user_id);
				$statement->bindValue(':permission', $user_rankid);
				$statement->bindValue(':name', Format::numeric2character($user_name));
				$statement->execute();
			} catch(Exception $e) {
				return false;
			}

			//check if user's dashboard account creation is successful or not
			if($this->memberInfo($user_id) != null) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}

	/**
	 * Gets member info, if the ID_MEMBER is known. ID_MEMBER can be easily obtained if the user is logged into the forum
	 *
	 * @param $user_id
	 *
	 * @return null
	 */
	public static function memberInfo($user_id) {
		global $connection, $db_info;

		if(databaseConnection()) {
			try {
				$sql = "SELECT * FROM {$db_info['member_tbl']} WHERE ID_MEMBER = :user_id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':user_id', $user_id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if(count($result) > 0) {
					return $result[0];
				} else {
					return null;
				}
			} catch(Exception $e) {

			}
		}

		return null;
	}

	/**
	 * Update username on the dashboard if username is changed on the forum
	 *
	 * @param $user_id
	 * @param $user_name
	 *
	 * @return bool
	 */
	public function updateUsername($user_id, $user_name) {
		global $connection, $db_info;

		if(databaseConnection()) {
			try {
				$sql = "UPDATE {$db_info['member_tbl']}
								SET
									membername = :name
								WHERE
									ID_MEMBER = :user_id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':name', Format::numeric2character($user_name));
				$statement->bindValue(':user_id', $user_id);
				$statement->execute();
			} catch(Exception $e) {
				return false;
			}
			return true;
		}
		return false;
	}


	/**
	 * Update user rank for the dashboard.
	 *
	 * @param $user_id
	 * @param $rank
	 *
	 * @return bool
	 */
	public function updateUserRank($user_id, $rank) {
		global $connection, $db_info;

		if(databaseConnection()) {
			try {
				$sql = "UPDATE {$db_info['member_tbl']}
								SET
									rank = :rank
								WHERE
									ID_MEMBER = :user_id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':rank', $rank);
				$statement->bindValue(':user_id', $user_id);
				$statement->execute();

				return true;
			} catch(Exception $e) { return false; }
		}
		return false;
	}


	/**
	 * Get count of all the addon submitted by the User
	 *
	 * @param $user_id
	 *
	 * @return int|null
	 */
	public function getAddonCountByUser($user_id) {
		global $connection, $db_info;

		if(databaseConnection()) {
			try {
				$sql = "SELECT * FROM {$db_info['addon_tbl']} WHERE ID_AUTHOR = :user_id AND status = 1";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':user_id', $user_id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				return count($result);
			} catch(Exception $e) {}
		}
		return null;
	}
	
	
	public static function SearchUsernames($usernameSearchTerm, $resultlimit=5)
	{
		global $connection, $db_info;
		
		if($usernameSearchTerm != null){
			
			if(databaseConnection()) {
				try {
					$sql = "SELECT * FROM {$db_info['member_tbl']} 
								WHERE
									membername LIKE :searchTerm 
								LIMIT :resultlimit";
					$statement = $connection->prepare($sql);
					$statement->bindValue(":searchTerm", '%'.$usernameSearchTerm.'%');
					$statement->bindValue(":resultlimit", (int)trim($resultlimit), PDO::PARAM_INT);
					$statement->execute();
					return $statement->fetchAll (PDO::FETCH_ASSOC);
				} catch(Exception $e) {
					return false;
				}
			}
		}
		
		return false;
	}



}
