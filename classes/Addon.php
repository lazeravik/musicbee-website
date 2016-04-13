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
	public function getAddonData($addon_id) {
		global $connection, $db_info, $mb;

		if($this->checkAddonExistenceById($addon_id)) {
			if(databaseConnection()) {
				try {
					$sql = "
					SELECT
					  {$db_info['addon_tbl']}.ID_ADDON,
					  {$db_info['addon_tbl']}.ID_AUTHOR,
					  {$db_info['addon_tbl']}.addon_title,
					  {$db_info['addon_tbl']}.addon_version,
					  {$db_info['addon_tbl']}.supported_mbversion,
					  {$db_info['addon_tbl']}.category,
					  {$db_info['addon_tbl']}.publish_date,
					  {$db_info['addon_tbl']}.update_date,
					  {$db_info['addon_tbl']}.tags,
					  {$db_info['addon_tbl']}.short_description,
					  {$db_info['addon_tbl']}.download_links,
					  {$db_info['addon_tbl']}.image_links,
					  {$db_info['addon_tbl']}.thumbnail,
					  {$db_info['addon_tbl']}.important_note,
					  {$db_info['addon_tbl']}.support_forum,
					  {$db_info['addon_tbl']}.readme_content_html,
					  {$db_info['addon_tbl']}.is_beta,
					  {$db_info['addon_tbl']}.status,
					  {$db_info['addon_tbl']}.lastStatus_moderatedBy,
					  {$db_info['member_tbl']}.membername,
					  {$db_info['member_tbl']}.rank,
					  COUNT(ID_LIKES) AS likesCount
					FROM
					  {$db_info['addon_tbl']}
					LEFT JOIN
					  {$db_info['member_tbl']}
					ON
					  {$db_info['addon_tbl']}.ID_AUTHOR = {$db_info['member_tbl']}.ID_MEMBER
					LEFT JOIN
					  {$db_info['likes_tbl']}
					ON
					  {$db_info['likes_tbl']}.ID_ADDON = {$db_info['addon_tbl']}.ID_ADDON
					WHERE
					  {$db_info['addon_tbl']}.ID_ADDON = :addon_id";

					$statement = $connection->prepare($sql);
					$statement->bindValue(':addon_id', $addon_id);
					$statement->execute();


					$result = $statement->fetchAll(PDO::FETCH_ASSOC)[0];

					$result['supported_mbversion'] = array_map(array(
							                                           $this,
							                                           "getMbVersions",
					                                           ), explode(",", $result['supported_mbversion']));
					$result['tags'] = explode(",", $result['tags']);
					$result['image_links'] = explode(",", $result['image_links']);
					$result['user']['already_liked'] = $this->is_rated($addon_id, $mb['user']['id']);

					return $result;
				} catch(Exception $e) {
				}
			}
		} else {
			return null;
		}
	}


	public function checkAddonExistenceById($addon_id) {
		global $connection,$db_info;
		if(databaseConnection()) {
			try {
				$sql = "SELECT COUNT(*) AS count FROM {$db_info['addon_tbl']} WHERE ID_ADDON = :addon_id";

				$statement = $connection->prepare($sql);
				$statement->bindValue(':addon_id', $addon_id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				return $result[0]['count'];
			} catch(Exception $e) {
			}
		}
	}

	public function is_rated($addon_id, $user_id) {
		global $connection,$db_info;
		if(databaseConnection()) {
			try {
				$sql = "SELECT * FROM {$db_info['likes_tbl']} WHERE ID_MEMBER = :user_id AND ID_ADDON = :addon_id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':user_id', $user_id);
				$statement->bindValue(':addon_id', $addon_id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if(count($result) > 0) {
					return true;
				} else {
					return false;
				}
			} catch(Exception $e) {

			}
		}
	}

	public function getTopMembers() {
		global $connection, $db_info;
		if(databaseConnection()) {
			try {
				$sql = "SELECT
						  {$db_info['member_tbl']}.ID_MEMBER,
						  {$db_info['member_tbl']}.membername,
						  {$db_info['member_tbl']}.rank,
						  upload.addonUploads
						FROM
						  {$db_info['member_tbl']}
						LEFT JOIN
						  (
						  SELECT
						    ID_AUTHOR,
						    COUNT(DISTINCT ID_ADDON) AS addonUploads
						  FROM
						    {$db_info['addon_tbl']}
						  WHERE
						    {$db_info['addon_tbl']}.status = 1
						  GROUP BY addons.ID_AUTHOR
						) upload
						ON
						  upload.ID_AUTHOR = {$db_info['member_tbl']}.ID_MEMBER
						WHERE upload.addonUploads > 0
						ORDER BY addonUploads DESC
						LIMIT 8";
				$statement = $connection->prepare($sql);
				$statement->execute();

				return $statement->fetchAll(PDO::FETCH_ASSOC);
			} catch(Exception $e) {

			}
		}
	}

	/**
	 * gets all the addons from this member
	 *
	 * @param int $id user id
	 * @param int $limit
	 *
	 * @return array
	 */
	public function getAddonListByMember($id, $limit) {
		global $connection, $db_info;
		if(databaseConnection()) {
			try {
				$sql = "SELECT
							{$db_info['addon_tbl']}.ID_ADDON,
				  	        ID_AUTHOR,
				  	        membername,
				  	        addon_title,
				  	        category,
				  	        thumbnail,
				  	        is_beta,
				  	        status,
							COUNT(ID_LIKES) AS likesCount
						FROM
							{$db_info['addon_tbl']}
							LEFT JOIN
								{$db_info['member_tbl']}
								ON
								{$db_info['addon_tbl']}.ID_AUTHOR = {$db_info['member_tbl']}.ID_MEMBER
							LEFT JOIN
								{$db_info['likes_tbl']}
                                on
                                {$db_info['addon_tbl']}.ID_ADDON = {$db_info['likes_tbl']}.ID_ADDON
						WHERE
							ID_AUTHOR = :id AND status = 1
						GROUP BY
							{$db_info['addon_tbl']}.ID_ADDON
						ORDER BY
							ID_ADDON DESC
						LIMIT
							{$limit}";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':id', $id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if(count($result) > 0) {
					return $result;
				} else {
					return null;
				}
			} catch(Exception $e) {

			}
		}
	}

	/**
	 * @param int $id member id
	 * @param int $limit
	 * @param int $stat addon status code
	 *
	 * @return array
	 */
	public function getAddonListByStatusAndMember($id, $limit, $stat) {
		global $connection,$db_info;
		if(databaseConnection()) {
			try {
				$sql = "SELECT * FROM {$db_info['addon_tbl']} WHERE ID_AUTHOR = :id AND status = {$stat} ORDER BY ID_ADDON DESC LIMIT {$limit}";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':id', $id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if(count($result) > 0) {
					return $result;
				} else {
					return null;
				}
			} catch(Exception $e) {

			}
		}
	}

	public function getMbVersions($val) {
		global $connection, $db_info;
		$ver = explode(",", $val); //create an array of supported musicbee versions
		if(databaseConnection()) {
			foreach($ver as $versionId) {
				try {
					$sql = "SELECT * FROM {$db_info['mb_all']} WHERE ID_ALLVERSIONS = :id";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':id', $versionId);
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					if(count($result) > 0) {
						return $result[0]['appname'];
					}
				} catch(Exception $e) {
					return null;
				}
			}

			return null;
		}
	}

	public function rate($addon_id, $user_id, $rate_val) {
		global $connection,$db_info;
		if(databaseConnection()) {
			try {
				if($rate_val == "like") {
					$sql = "INSERT INTO {$db_info['likes_tbl']} SET ID_MEMBER = :user_id, ID_ADDON = :addon_id";
				} elseif($rate_val == "unlike") {
					$sql = "DELETE FROM {$db_info['likes_tbl']} WHERE ID_MEMBER = :user_id AND ID_ADDON = :addon_id";
				}
				$statement = $connection->prepare($sql);
				$statement->bindValue(':user_id', $user_id);
				$statement->bindValue(':addon_id', $addon_id);
				$statement->execute();

				return true;
			} catch(Exception $e) {

			}
		}
	}


	public function getRating($addon_id, $raw = false) {
		global $connection,$db_info;
		if(databaseConnection()) {
			try {
				$sql = "SELECT * FROM {$db_info['likes_tbl']} WHERE ID_ADDON = :id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':id', $addon_id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if(count($result) >= 0) {
					return count($result);
				}
			} catch(Exception $e) {
				return null;
			}
		}
	}

	public function getAddonInfo($id) {
		global $connection,$db_info;
		if(databaseConnection()) {
			try {
				if($id != null) {
					$sql = "
						SELECT * 
						FROM {$db_info['addon_tbl']}
								LEFT JOIN 
							{$db_info['member_tbl']}
								ON
							{$db_info['addon_tbl']}.ID_AUTHOR = {$db_info['member_tbl']}.ID_MEMBER
						WHERE 
							ID_ADDON = :id";

					$statement = $connection->prepare($sql);
					$statement->bindValue(':id', $id);
				}
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if(count($result) > 0) {
					return $result;
				}
			} catch(Exception $e) {
				return $e;
			}
		}
	}


}
