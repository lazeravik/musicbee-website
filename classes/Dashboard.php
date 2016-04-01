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
class Dashboard
{
	private $addon_tbl = SITE_ADDON;
	private $member_tbl = SITE_MEMBER_TBL;
	private $likes_tbl = SITE_ADDON_LIKE;
	private $download_stat_tbl = SITE_DOWNLOAD_STAT;

	public static function getStatus($id) {
		switch($id) {
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

	public function verifyAuthor($member_id, $addon_id) {
		global $connection;
		if(databaseConnection()) {
			try {
				$sql = "SELECT * FROM {$this->addon_tbl} WHERE ID_ADDON = :addon_id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':addon_id', $addon_id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if(count($result) > 0) {
					if($result[0]['ID_AUTHOR'] == $member_id) {
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
			} catch(Exception $e) {

			}
		}
	}

	/**
	 * @param $addon_id
	 *
	 * @return bool
	 * deletes an addon from database
	 */
	public function deleteAddon($addon_id) {
		global $connection;
		if(databaseConnection()) {
			try {
				//delete query
				$sql = "DELETE FROM {$this->addon_tbl} WHERE ID_ADDON = :addon_id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':addon_id', $addon_id);
				$statement->execute();

				//check if the addon is truly deleted or not
				$sql = "SELECT ID_ADDON FROM {$this->addon_tbl} WHERE ID_ADDON = :addon_id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':addon_id', $addon_id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if(count($result) > 0) {
					return false; //if record found then deletation failed, return false
				} else {
					return true; //else we successfully deleted the addon
				}
			} catch(Exception $e) {

			}
		}
	}


	public function submit($rankid, $authorId, $readme_html, $type) {
		global $connection;
		if($rankid != 10) {
			$status = 1; //user has higher rank then we automatically approve
		} else {
			$status = 0; //otherwise need approval
		}
		$addon_id = (isset($_POST['record_id'])) ? $_POST['record_id'] : "";
		$lastModeratedBy = $authorId;
		$readme = (isset($_POST['readme'])) ? $_POST['readme'] : "";
		$important_note = (isset($_POST['important_note'])) ? $_POST['important_note'] : "";
		$support = (isset($_POST['support'])) ? $_POST['support'] : "";
		$screenshot_links = (isset($_POST['screenshot_links'])) ? $tags = implode(',', $_POST['screenshot_links']) : "";
		$thumb = (isset($_POST['thumb'])) ? $_POST['thumb'] : "";
		$dlink = (isset($_POST['dlink'])) ? $_POST['dlink'] : "";
		$description = (isset($_POST['description'])) ? $_POST['description'] : "";
		$addonver = (!empty($_POST['addonver'])) ? $_POST['addonver'] : "1.0";

		if(isset($_POST['beta'])) {
			if($_POST['beta'] == 1 || $_POST['beta'] == 0) {
				$beta = $_POST['beta'];
			} else {
				$beta = 0;
			}
		} else {
			$beta = 0;
		}

		$publish_date = date("F j, Y"); //current date
		$update_date = date("F j, Y"); //current date

		if(databaseConnection()) {
			try {
				if($type == "submit") {
					$sql = "INSERT
								INTO {$this->addon_tbl}
							SET
								ID_AUTHOR = :id_author,
								tags = :tags,
								supported_mbversion = :supported_mbversion,
								addon_title = :addon_title,
								addon_type = :addon_type,
								addon_version = :addon_version,
								short_description = :short_description,
								download_links = :download_links,
								image_links = :image_links,
								thumbnail = :thumbnail,
								support_forum = :support_forum,
								important_note = :important_note,
								readme_content = :readme_content,
								readme_content_html = :readme_content_html,
								is_beta = :is_beta,
								status = :status,
								publish_date = :publish_date,
								lastStatus_moderatedBy = :lastStatus_moderatedBy";
				} elseif($type == "update") {
					$sql = "UPDATE
								{$this->addon_tbl}
							SET
								tags = :tags,
								supported_mbversion = :supported_mbversion,
								addon_title = :addon_title,
								addon_type = :addon_type,
								addon_version = :addon_version,
								short_description = :short_description,
								download_links = :download_links,
								image_links = :image_links,
								thumbnail = :thumbnail,
								support_forum = :support_forum,
								important_note = :important_note,
								readme_content = :readme_content,
								readme_content_html = :readme_content_html,
								is_beta = :is_beta,
								status = :status,
								update_date = :update_date,
								lastStatus_moderatedBy = :lastStatus_moderatedBy
							WHERE
								ID_ADDON = :addon_id";
				}
				$statement = $connection->prepare($sql);
				if($type == "submit") {
					$statement->bindValue(':id_author', $authorId);
					$statement->bindValue(':publish_date', $publish_date);
				} elseif($type == "update") {
					$statement->bindValue(':update_date', $update_date);
					$statement->bindValue(':addon_id', $addon_id);
				}
				$statement->bindValue(':tags', htmlspecialchars(trim($_POST['tag'])));
				$statement->bindValue(':supported_mbversion', htmlspecialchars($_POST['mbSupportedVer']));
				$statement->bindValue(':addon_title', htmlspecialchars(trim($_POST['title'])));
				$statement->bindValue(':addon_type', htmlspecialchars($_POST['type']));
				$statement->bindValue(':addon_version', htmlspecialchars(trim($addonver)));
				$statement->bindValue(':short_description', htmlspecialchars(trim($description)));
				$statement->bindValue(':download_links', htmlspecialchars(trim($dlink)));
				$statement->bindValue(':image_links', htmlspecialchars($screenshot_links));
				$statement->bindValue(':thumbnail', htmlspecialchars($thumb));
				$statement->bindValue(':support_forum', htmlspecialchars(trim($support)));
				$statement->bindValue(':important_note', htmlspecialchars(trim($important_note)));
				$statement->bindValue(':readme_content', $readme);
				$statement->bindValue(':readme_content_html', $readme_html);
				$statement->bindValue(':is_beta', $beta);
				$statement->bindValue(':status', $status);
				$statement->bindValue(':lastStatus_moderatedBy', $lastModeratedBy);
				$statement->execute();
			} catch(Exception $e) {
				return false;
			}

			return true;
		}
	}

	public function getTopVotedAddonsByAuthor($author_id, $limit = 10) {
		global $connection;

		if(databaseConnection()) {
			try {
				$sql = "SELECT
					{$this->addon_tbl}.ID_ADDON,
					{$this->addon_tbl}.addon_title,
					{$this->addon_tbl}.addon_type,
					{$this->addon_tbl}.status,
					COUNT(ID_LIKES) AS likesCount
					FROM
					{$this->likes_tbl}
					LEFT JOIN
					{$this->addon_tbl}
					ON
					{$this->likes_tbl}.ID_ADDON = {$this->addon_tbl}.ID_ADDON
					WHERE
					{$this->addon_tbl}.status = 1
					AND
					{$this->addon_tbl}.ID_AUTHOR = :author_id
					GROUP BY
					{$this->addon_tbl}.ID_ADDON
					ORDER BY
					likesCount
					DESC
					LIMIT {$limit}";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':author_id', $author_id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				return $result;
			} catch(Exception $e) {
			}
		}
	}

	public function getMostDownloadedAddonsByAuthor($author_id, $limit = 10) {
		global $connection;

		if(databaseConnection()) {
			try {
				$sql = "SELECT
						{$this->addon_tbl}.ID_ADDON,
						{$this->addon_tbl}.addon_title,
						{$this->addon_tbl}.addon_type,
						{$this->addon_tbl}.status,
						COUNT(STAT_ID) AS downloadCount
					FROM
						{$this->download_stat_tbl}
					LEFT JOIN
						{$this->addon_tbl}
						ON
						{$this->addon_tbl}.ID_ADDON = {$this->download_stat_tbl}.ID
					WHERE
						{$this->addon_tbl}.status = 1
						AND
						{$this->addon_tbl}.ID_AUTHOR = :author_id
					GROUP BY
						{$this->addon_tbl}.ID_ADDON
					ORDER BY
						downloadCount
					DESC
						LIMIT {$limit}";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':author_id', $author_id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				return $result;
			} catch(Exception $e) {
			}
		}
	}

	public function getAllUnApprovedAddons() {
		global $connection;

		if(databaseConnection()) {
			try {
				$sql = "SELECT
					{$this->addon_tbl}.ID_ADDON, {$this->addon_tbl}.addon_title, {$this->addon_tbl}.addon_type, {$this->addon_tbl}.status, {$this->member_tbl}.membername, {$this->member_tbl}.ID_MEMBER
					FROM
					{$this->member_tbl}
					LEFT JOIN
					{$this->addon_tbl}
					ON
					{$this->addon_tbl}.ID_AUTHOR = {$this->member_tbl}.ID_MEMBER
					WHERE
					{$this->addon_tbl}.status = 0";
				$statement = $connection->prepare($sql);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				return $result;
			} catch(Exception $e) {
			}
		}
	}


	/**
	 * @param $addon_id
	 *
	 * @return bool
	 * deletes an addon from database
	 */
	public function getAddonStatus($addon_id) {
		global $connection;
		if(databaseConnection()) {
			try {
				$sql = "SELECT status FROM {$this->addon_tbl} WHERE ID_ADDON = :addon_id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':addon_id', $addon_id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if(count($result) > 0) {
					return $result[0]; //if record found then deletation failed, return false
				} else {
					return null; //else we successfully deleted the addon
				}
			} catch(Exception $e) {

			}
		}
	}


	public function updateAddonStatus($id, $status, $updater_id) {
		global $connection;

		$update_date = date("F j, Y, g:i a"); //current date

		if(databaseConnection()) {
			try {
				$sql = "UPDATE {$this->addon_tbl}
							SET
								status = :status,
								lastStatus_moderatedBy = :lastStatus_moderatedBy,
								update_date = :update_date
							WHERE
								ID_ADDON = :addon_id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':status', $status);
				$statement->bindValue(':addon_id', $id);
				$statement->bindValue(':lastStatus_moderatedBy', $updater_id);
				$statement->bindValue(':update_date', $update_date);
				$statement->execute();

				return true;
			} catch(Exception $e) {
			}
		}
	}

	/**
	 * gets all the addons from this member
	 *
	 * @param int $id user id
	 *
	 * @return array
	 */
	public function getAllAddonByMember($id) {
		global $connection;
		if(databaseConnection()) {
			try {
				$sql = "SELECT
							*
						FROM
							{$this->addon_tbl}
								LEFT JOIN
							{$this->member_tbl}
								on
							{$this->addon_tbl}.ID_AUTHOR = {$this->member_tbl}.ID_MEMBER
						WHERE
							ID_AUTHOR = :id
						ORDER BY
							ID_ADDON DESC";
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
	 * @param int $id   member id
	 * @param int $stat addon status code
	 *
	 * @return array
	 */
	public function getAllAddonCountByStatusAndMember($id, $stat) {
		global $connection;
		if(databaseConnection()) {
			try {
				$sql = "SELECT
							COUNT(*) AS count
						FROM
							{$this->addon_tbl}
						WHERE
							ID_AUTHOR = :id
							AND
							status = ".$stat."
						ORDER BY ID_ADDON DESC";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':id', $id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				return $result[0]['count'];

			} catch(Exception $e) {

			}
		}
	}


	/**
	 * Check if an addon exists with similer title
	 *
	 * @param string $title
	 * @param int    $exception
	 *
	 * @return bool
	 */
	function addonExists($title, $exception = null) {
		global $connection;
		if(databaseConnection()) {
			try {
				if($exception == null) {
					$sql = "SELECT * FROM {$this->addon_tbl} WHERE addon_title = :title";
					$statement = $connection->prepare($sql);
				} else {
					$sql = "SELECT * FROM {$this->addon_tbl} WHERE addon_title = :title AND NOT ID_ADDON = :id_addon";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':id_addon', $exception);
				}
				$statement->bindValue(':title', $title);
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

}

