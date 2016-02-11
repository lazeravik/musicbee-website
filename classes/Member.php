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
//Gets the member rank, if the ID_MEMBER is known. ID_MEMBER can be easily obtained if the user is logged into the forum
public function memberInfo($user_id)
{
	global $connection;
	if (databaseConnection()) {
		try {
			$sql = "SELECT * FROM ".SITE_MEMBER_TBL." WHERE ID_MEMBER = :user_id";
			$statement = $connection->prepare($sql);
			$statement->bindValue(':user_id', $user_id);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) > 0)
			{
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
	 * @return string
	 * @todo improve it by using array with foreach switch, it will make it more feature rich and flexible
     */
	public function rankName($rankid)
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
?>