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

		public function verifyAuthor($member_id, $addon_id)
		{
			global $connection;
			if (databaseConnection()) {
				try {
					$sql = "SELECT * FROM " . SITE_ADDON . " WHERE ID_ADDON = :addon_id";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':addon_id', $addon_id);
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					if (count($result) > 0) {
						if ($result[0]['ID_AUTHOR'] == $member_id) {
							return true;
						} else {
							return false;
						}
					} else {
						return false;
					}
				} catch (Exception $e) {

				}
			}
		}

		/**
		 * @param $addon_id
		 *
		 * @return bool
		 * deletes an addon from database
		 */
		public function deleteAddon($addon_id)
		{
			global $connection;
			if (databaseConnection()) {
				try {
					//delete query
					$sql = "DELETE FROM " . SITE_ADDON . " WHERE ID_ADDON = :addon_id";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':addon_id', $addon_id);
					$statement->execute();

					//check if the addon is truly deleted or not
					$sql = "SELECT ID_ADDON FROM " . SITE_ADDON . " WHERE ID_ADDON = :addon_id";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':addon_id', $addon_id);
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					if (count($result) > 0) {
						return false; //if record found then deletation failed, return false
					} else {
						return true; //else we successfully deleted the addon
					}
				} catch (Exception $e) {

				}
			}
		}

		public function submit($rankid, $authorId, $readme_html, $type)
		{
			global $connection;
			if ($rankid != 10) {
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
			$color = (isset($_POST['color'])) ? $_POST['color'] : "default";

			$publish_date = date("F j, Y"); //current date
			$update_date = date("F j, Y"); //current date

			if (databaseConnection()) {
				try {
					if ($type == "submit") {
						$sql = 'INSERT
						INTO '.SITE_ADDON.'
						SET
						ID_AUTHOR = :id_author,
						COLOR_ID = :color,
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
						lastStatus_moderatedBy = :lastStatus_moderatedBy';
					} elseif ($type == "update") {
						$sql = 'UPDATE '.SITE_ADDON.'
						SET
						COLOR_ID = :color,
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
						WHERE ID_ADDON = :addon_id';
					}
					$statement = $connection->prepare($sql);
					if ($type == "submit") {
						$statement->bindValue(':id_author', $authorId);
						$statement->bindValue(':publish_date', $publish_date);
					} elseif ($type == "update") {
						$statement->bindValue(':update_date', $update_date);
						$statement->bindValue(':addon_id', $addon_id);
					}
					$statement->bindValue(':tags', htmlspecialchars($_POST['tag']));
					$statement->bindValue(':supported_mbversion', htmlspecialchars($_POST['mbSupportedVer']));
					$statement->bindValue(':addon_title', htmlspecialchars($_POST['title']));
					$statement->bindValue(':addon_type', htmlspecialchars($_POST['type']));
					$statement->bindValue(':addon_version', htmlspecialchars($addonver));
					$statement->bindValue(':short_description', htmlspecialchars($description));
					$statement->bindValue(':download_links', htmlspecialchars($dlink));
					$statement->bindValue(':image_links', htmlspecialchars($screenshot_links));
					$statement->bindValue(':thumbnail', htmlspecialchars($thumb));
					$statement->bindValue(':support_forum', htmlspecialchars($support));
					$statement->bindValue(':important_note', htmlspecialchars($important_note));
					$statement->bindValue(':readme_content', $readme);
					$statement->bindValue(':readme_content_html', $readme_html);
					$statement->bindValue(':is_beta', "0");
					$statement->bindValue(':status', $status);
					$statement->bindValue(':lastStatus_moderatedBy', $lastModeratedBy);
					$statement->bindValue(':color', htmlspecialchars($color));
					$statement->execute();
				} catch (Exception $e) {
					return false;
				}

				return true;
			}
		}

		public function getTopVotedAddonsByAuthor($author_id, $limit = 10)
		{
			global $connection;

			if (databaseConnection()) {
				try {
					$sql = "SELECT
					".SITE_ADDON.".ID_ADDON, ".SITE_ADDON.".addon_title, ".SITE_ADDON.".addon_type, ".SITE_ADDON.".status,
					COUNT(ID_LIKES) AS likesCount
					FROM
					".SITE_ADDON_LIKE."
					LEFT JOIN
					".SITE_ADDON."
					ON
					".SITE_ADDON_LIKE.".ID_ADDON = ".SITE_ADDON.".ID_ADDON
					WHERE 
					".SITE_ADDON.".status = 1
					AND
					".SITE_ADDON.".ID_AUTHOR = :author_id
					GROUP BY
					".SITE_ADDON.".ID_ADDON
					ORDER BY
					likesCount
					DESC 
					LIMIT {$limit}";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':author_id', $author_id);
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);

					return $result;
				} catch (Exception $e) {}
			}
		}

		public function getMostDownloadedAddonsByAuthor($author_id, $limit = 10)
		{
			global $connection;

			if (databaseConnection()) {
				try {
					$sql = "SELECT
					".SITE_ADDON.".ID_ADDON, ".SITE_ADDON.".addon_title, ".SITE_ADDON.".addon_type, ".SITE_ADDON.".status,
					COUNT(STAT_ID) AS downloadCount
					FROM
					".SITE_DOWNLOAD_STAT."
					LEFT JOIN
					".SITE_ADDON."
					ON
					".SITE_ADDON.".ID_ADDON =  ".SITE_DOWNLOAD_STAT.".ID
					WHERE 
					".SITE_ADDON.".status = 1
					AND
					".SITE_ADDON.".ID_AUTHOR = :author_id
					GROUP BY
					".SITE_ADDON.".ID_ADDON
					ORDER BY
					downloadCount
					DESC 
					LIMIT {$limit}";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':author_id', $author_id);
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);

					return $result;
				} catch (Exception $e) {}
			}
		}

		public function getAllUnApprovedAddons()
		{
			global $connection;

			if (databaseConnection()) {
				try {
					$sql = "SELECT
					".SITE_ADDON.".ID_ADDON, ".SITE_ADDON.".addon_title, ".SITE_ADDON.".addon_type, ".SITE_ADDON.".status, ".SITE_MEMBER_TBL.".membername, ".SITE_MEMBER_TBL.".ID_MEMBER
					FROM
					".SITE_MEMBER_TBL."
					LEFT JOIN
					".SITE_ADDON."
					ON
					".SITE_ADDON.".ID_AUTHOR = ".SITE_MEMBER_TBL.".ID_MEMBER
					WHERE 
					".SITE_ADDON.".status = 0";
					$statement = $connection->prepare($sql);
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);

					return $result;
				} catch (Exception $e) {}
			}
		}

		public function updateAddonStatus($id, $status)
		{
			global $connection;

			if (databaseConnection()) {
				try {
					$sql = 'UPDATE '.SITE_ADDON.'
							SET
								status = :status
							WHERE 
								ID_ADDON = :addon_id';
					$statement = $connection->prepare($sql);
					$statement->bindValue(':status', $status);
					$statement->bindValue(':addon_id', $id);
					$statement->execute();

					return true;
				} catch (Exception $e) {}
			}
		}

		/**
		 * gets all the addons from this member
		 * @param int $id user id
		 *
		 * @return array
		 */
		public function getAllAddonByMember($id)
		{
			global $connection;
			if (databaseConnection()) {
				try {
					$sql = "
						SELECT
							*
						FROM
							".SITE_ADDON."
								LEFT JOIN
							".SITE_MEMBER_TBL."
								on
							".SITE_ADDON.".ID_AUTHOR = ".SITE_MEMBER_TBL.".ID_MEMBER
						WHERE
							ID_AUTHOR = :id
						ORDER BY
							ID_ADDON DESC";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':id', $id);
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					if (count($result) > 0) {
						return $result;
					} else {
						return null;
					}
				} catch (Exception $e) {

				}
			}
		}


		/**
		 * @param int $id member id
		 * @param int $stat addon status code
		 *
		 * @return array
		 */
		public function getAllAddonByStatusAndMember($id, $stat)
		{
			global $connection;
			if (databaseConnection()) {
				try {
					$sql = "SELECT * FROM " . SITE_ADDON . " WHERE ID_AUTHOR = :id AND status = " . $stat . " ORDER BY ID_ADDON DESC";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':id', $id);
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					if (count($result) > 0) {
						return $result;
					} else {
						return null;
					}
				} catch (Exception $e) {

				}
			}
		}
		public static function getStatus($id)
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







		}

