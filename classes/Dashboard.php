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
 * Handles Dashboard related stuff
 * @author : AvikB;
 *
 * @todo mention charset in htmlspecialchars()
 */
class Dashboard
{
	public function saveSiteSetting() {
		global $connection,$db_info;

		$showPgaeLoadTime = (isset($_POST['pageloadtime'])) ? 1 : 0;
		$addonSubmissionOn = (isset($_POST['submission'])) ? 1 : 0;
		$imgurUploadOn = (isset($_POST['imguron'])) ? 1 : 0;
		$maxSubmitWithOutApproval = (isset($_POST['unapproved_addon_max'])) ? htmlspecialchars($_POST['unapproved_addon_max']) : 10;
		$imgurClientID = $_POST['imgurClientID'];
		$imgurClientSecret = $_POST['imgurClientSecret'];
		$paypalDonationLink = htmlspecialchars($_POST['paypalDonationLink']);
		$twitterLink = htmlspecialchars($_POST['twitterLink']);
		$wikiaLink = htmlspecialchars($_POST['wikiaLink']);
		$wishlistLink = htmlspecialchars($_POST['wishlistLink']);
		$websiteApiLink = htmlspecialchars($_POST['websiteApiLink']);
		$musicbeeApiLink = htmlspecialchars($_POST['musicbeeApiLink']);
		$websiteBugLink = htmlspecialchars($_POST['websiteBugLink']);
		$musicbeeBugLink = htmlspecialchars($_POST['musicbeeBugLink']);
		$presskitLink = htmlspecialchars($_POST['presskitLink']);
		$eliteRequirement = htmlspecialchars($_POST['eliteRequirement']);
		$selfApprovalRequirement = htmlspecialchars($_POST['selfApprovalRequirement']);

		$bindedVal = array(
				$showPgaeLoadTime,
				$addonSubmissionOn,
				$imgurUploadOn,
				$maxSubmitWithOutApproval,
				$imgurClientID,
				$imgurClientSecret,
				$paypalDonationLink,
				$twitterLink,
				$wikiaLink,
				$wishlistLink,
				$websiteApiLink,
				$musicbeeApiLink,
				$websiteBugLink,
				$musicbeeBugLink,
				$presskitLink,
				$eliteRequirement,
				$selfApprovalRequirement,
		);


		if(databaseConnection()) {
			try {
				$sql = "
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'showPgaeLoadTime';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'addonSubmissionOn';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'imgurUploadOn';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'maxSubmitWithOutApproval';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'imgurClientID';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'imgurClientSecret';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'paypalDonationLink';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'twitterLink';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'wikiaLink';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'wishlistLink';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'websiteApiLink';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'musicbeeApiLink';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'websiteBugLink';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'musicbeeBugLink';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'presskitLink';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'eliteRequirement';
						UPDATE {$db_info['settings_tbl']} SET value = ? WHERE {$db_info['settings_tbl']}.variable = 'selfApprovalRequirement';
						";

				//var_dump(showQuery($sql, $bindedVal));

				$statement = $connection->prepare($sql);
				$statement->execute($bindedVal);

				return true;
			} catch(Exception $e) {
				return false;
			}
		}
	}


	public function verifyAuthor($member_id, $addon_id) {
		global $connection, $db_info;
		if(databaseConnection()) {
			try {
				$sql = "SELECT * FROM {$db_info['addon_tbl']} WHERE ID_ADDON = :addon_id";
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
		global $connection, $db_info;
		if(databaseConnection()) {
			try {
				//delete query
				$sql = "DELETE FROM {$db_info['addon_tbl']} WHERE ID_ADDON = :addon_id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':addon_id', $addon_id);
				$statement->execute();

				//check if the addon is truly deleted or not
				$sql = "SELECT ID_ADDON FROM {$db_info['addon_tbl']} WHERE ID_ADDON = :addon_id";
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


	/**
	 * Submit addons or Update existing addons
	 *
	 * @param $readme_html
	 * @param $type
	 *
	 * @return bool
	 */
	public function submit($readme_html, $type) {
		global $connection, $db_info, $mb;

		$status = (!$mb['user']['need_approval'])? 1 : 0;
		$addon_id = (isset($_POST['record_id'])) ? $_POST['record_id'] : "";
		$authorId = $mb['user']['id'];
		$lastModeratedBy = $mb['user']['id'];
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
		$update_date = date("F j, Y, g:i a"); //current date

		if(databaseConnection()) {
			try {
				if($type == "submit") {
					$sql = "INSERT
								INTO {$db_info['addon_tbl']}
							SET
								ID_AUTHOR = :id_author,
								tags = :tags,
								supported_mbversion = :supported_mbversion,
								addon_title = :addon_title,
								category = :category,
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
								{$db_info['addon_tbl']}
							SET
								tags = :tags,
								supported_mbversion = :supported_mbversion,
								addon_title = :addon_title,
								category = :category,
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
				$statement->bindValue(':addon_title', htmlspecialchars(trim($_POST['title']), ENT_QUOTES, "UTF-8"));
				$statement->bindValue(':category', htmlspecialchars($_POST['type']));
				$statement->bindValue(':addon_version', htmlspecialchars(trim($addonver)));
				$statement->bindValue(':short_description', htmlspecialchars(trim($description), ENT_QUOTES, "UTF-8"));
				$statement->bindValue(':download_links', htmlspecialchars(trim($dlink)));
				$statement->bindValue(':image_links', htmlspecialchars($screenshot_links));
				$statement->bindValue(':thumbnail', htmlspecialchars($thumb));
				$statement->bindValue(':support_forum', htmlspecialchars(trim($support)));
				$statement->bindValue(':important_note', htmlspecialchars(trim($important_note), ENT_QUOTES, "UTF-8"));
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
		global $connection, $db_info;

		if(databaseConnection()) {
			try {
				$sql = "SELECT
					{$db_info['addon_tbl']}.ID_ADDON,
					{$db_info['addon_tbl']}.addon_title,
					{$db_info['addon_tbl']}.category,
					{$db_info['addon_tbl']}.status,
					COUNT(ID_LIKES) AS likesCount
					FROM
					{$db_info['likes_tbl']}
					LEFT JOIN
					{$db_info['addon_tbl']}
					ON
					{$db_info['likes_tbl']}.ID_ADDON = {$db_info['addon_tbl']}.ID_ADDON
					WHERE
					{$db_info['addon_tbl']}.status = 1
					AND
					{$db_info['addon_tbl']}.ID_AUTHOR = :author_id
					GROUP BY
					{$db_info['addon_tbl']}.ID_ADDON
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
		global $connection,$db_info;

		if(databaseConnection()) {
			try {
				$sql = "SELECT
						{$db_info['addon_tbl']}.ID_ADDON,
						{$db_info['addon_tbl']}.addon_title,
						{$db_info['addon_tbl']}.category,
						{$db_info['addon_tbl']}.status,
						COUNT(STAT_ID) AS downloadCount
					FROM
						{$db_info['download_stat_tbl']}
					LEFT JOIN
						{$db_info['addon_tbl']}
						ON
						{$db_info['addon_tbl']}.ID_ADDON = {$db_info['download_stat_tbl']}.ID
					WHERE
						{$db_info['addon_tbl']}.status = 1
						AND
						{$db_info['addon_tbl']}.ID_AUTHOR = :author_id
					GROUP BY
						{$db_info['addon_tbl']}.ID_ADDON
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
		global $connection,$db_info;

		if(databaseConnection()) {
			try {
				$sql = "SELECT
					{$db_info['addon_tbl']}.ID_ADDON, {$db_info['addon_tbl']}.addon_title, {$db_info['addon_tbl']}.category, {$db_info['addon_tbl']}.status, {$db_info['member_tbl']}.membername, {$db_info['member_tbl']}.ID_MEMBER
					FROM
					{$db_info['member_tbl']}
					LEFT JOIN
					{$db_info['addon_tbl']}
					ON
					{$db_info['addon_tbl']}.ID_AUTHOR = {$db_info['member_tbl']}.ID_MEMBER
					WHERE
					{$db_info['addon_tbl']}.status = 0";
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
		global $connection,$db_info;
		if(databaseConnection()) {
			try {
				$sql = "SELECT status FROM {$db_info['addon_tbl']} WHERE ID_ADDON = :addon_id";
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
		global $connection,$db_info;

		$update_date = date("F j, Y, g:i a"); //current date

		if(databaseConnection()) {
			try {
				$sql = "UPDATE {$db_info['addon_tbl']}
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
		global $connection,$db_info;
		if(databaseConnection()) {
			try {
				$sql = "SELECT
							*
						FROM
							{$db_info['addon_tbl']}
								LEFT JOIN
							{$db_info['member_tbl']}
								on
							{$db_info['addon_tbl']}.ID_AUTHOR = {$db_info['member_tbl']}.ID_MEMBER
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
		global $connection,$db_info;
		if(databaseConnection()) {
			try {
				$sql = "SELECT
							COUNT(*) AS count
						FROM
							{$db_info['addon_tbl']}
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
		global $connection,$db_info;
		if(databaseConnection()) {
			try {
				if($exception == null) {
					$sql = "SELECT * FROM {$db_info['addon_tbl']} WHERE addon_title = :title";
					$statement = $connection->prepare($sql);
				} else {
					$sql = "SELECT * FROM {$db_info['addon_tbl']} WHERE addon_title = :title AND NOT ID_ADDON = :id_addon";
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




	public function getAddonDownloadCount($author_id) {
		global $connection,$db_info;

		if (databaseConnection()) {
			try {
				$sql = "SELECT
							STAT_ID, ip_address,ID_ADDON, ID_AUTHOR
						FROM
							{$db_info['download_stat_tbl']}
							LEFT JOIN
								{$db_info['addon_tbl']}
								ON
								{$db_info['download_stat_tbl']}.ID = {$db_info['addon_tbl']}.ID_ADDON ";
				if(!empty($author_id)){
					$sql .= "WHERE
								status = 1
								AND
								ID_AUTHOR = :author_id";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':author_id', $author_id);
				} else {
					$statement = $connection->prepare($sql);
				}
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				return count($result);
			} catch (Exception $e) {}
		}
	}

	public function getAddonLikeCount($author_id) {
		global $connection,$db_info;

		if (databaseConnection()) {
			try {
				$sql = "SELECT
							ID_LIKES
						FROM
							{$db_info['likes_tbl']}
							LEFT JOIN
								{$db_info['addon_tbl']}
								ON
								{$db_info['likes_tbl']}.ID_ADDON = {$db_info['addon_tbl']}.ID_ADDON ";

				if(!empty($author_id)){
					$sql .= "WHERE
								status = 1
								AND
								ID_AUTHOR = :author_id";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':author_id', $author_id);
				} else {
					$statement = $connection->prepare($sql);
				}
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				return count($result);
			} catch (Exception $e) {}
		}
	}


	public function getAllAddonCountByStatus($stat) {
		global $connection,$db_info;

		if(databaseConnection()) {
			try {
				$sql = "SELECT
							COUNT(*) AS count
						FROM
							{$db_info['addon_tbl']}
						WHERE
							status = {$stat}";
				$statement = $connection->prepare($sql);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				return $result[0]['count'];

			} catch(Exception $e) {

			}
		}
	}


	public function getAllAddonCount() {
		global $connection,$db_info;

		if(databaseConnection()) {
			try {
				$sql = "SELECT
							COUNT(*) AS count
						FROM
							{$db_info['addon_tbl']}";
				$statement = $connection->prepare($sql);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				return $result[0]['count'];

			} catch(Exception $e) {

			}
		}
	}

	public function getAllMemberCount() {
		global $connection, $db_info;

		if(databaseConnection()) {
			try {
				$sql = "SELECT
							COUNT(*) AS count
						FROM
							{$db_info['member_tbl']}";
				$statement = $connection->prepare($sql);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				return $result[0]['count'];

			} catch(Exception $e) {

			}
		}
	}

	public function getAllAddonPublisherCount() {
		global $connection, $db_info;

		if(databaseConnection()) {
			try {
				$sql = "SELECT
						  COUNT(*) as publisherCount
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
						WHERE
							upload.addonUploads > 0";
				$statement = $connection->prepare($sql);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				return $result[0]['publisherCount'];

			} catch(Exception $e) {

			}
		}
	}




}

