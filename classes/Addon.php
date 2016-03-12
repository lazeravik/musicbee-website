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
//gets all the addons from this member
		public function getAddonListbyMember($id, $limit)
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
							ID_ADDON DESC 
						LIMIT 
							".$limit;
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


		public function getUnApprovedAddonsbyMember($id, $limit, $stat)
		{
			global $connection;
			if (databaseConnection()) {
				try {
					$sql = "SELECT * FROM " . SITE_ADDON . " WHERE ID_AUTHOR = :id AND status = " . $stat . " ORDER BY ID_ADDON DESC LIMIT " . $limit;
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

		public function getMbVersions($val)
		{
			global $connection;
			$ver = explode(",", $val); //create an array of supported musicbee versions
			if (databaseConnection()) {
				foreach ($ver as $versionId) {
					try {
						$sql = "SELECT * FROM " . SITE_MB_ALL_VERSION_TBL . " WHERE ID_ALLVERSIONS = :id";
						$statement = $connection->prepare($sql);
						$statement->bindValue(':id', $versionId);
						$statement->execute();
						$result = $statement->fetchAll(PDO::FETCH_ASSOC);
						if (count($result) > 0) {
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
					$sql = "SELECT * FROM " . SITE_ADDON_LIKE . " WHERE ID_MEMBER = :user_id AND ID_ADDON = :addon_id";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':user_id', $user_id);
					$statement->bindValue(':addon_id', $addon_id);
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					if (count($result) > 0) {
						return true;
					} else {
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
						$sql = "INSERT INTO " . SITE_ADDON_LIKE . " SET ID_MEMBER = :user_id, ID_ADDON = :addon_id";
					} elseif ($rate_val == "unlike") {
						$sql = "DELETE FROM " . SITE_ADDON_LIKE . " WHERE ID_MEMBER = :user_id AND ID_ADDON = :addon_id";
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
					$sql = "SELECT * FROM " . SITE_ADDON_LIKE . " WHERE ID_ADDON = :id";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':id', $addon_id);
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					if (count($result) >= 0) {
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
					if ($id != null) {
						$sql = "
						SELECT * 
						FROM ".SITE_ADDON." 
								LEFT JOIN 
							".SITE_MEMBER_TBL." 
								on 
							".SITE_ADDON.".ID_AUTHOR = ".SITE_MEMBER_TBL.".ID_MEMBER
						WHERE 
							ID_ADDON = :id";

						$statement = $connection->prepare($sql);
						$statement->bindValue(':id', $id);
					}
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					if (count($result) > 0) {
						return $result;
					}
				} catch (Exception $e) {
					return $e;
				}
			}
		}


		public function getMemberIdByName($query)
		{
			global $connection;
			if (databaseConnection()) {
				$query = str_replace(":author", "", $query);
				$sql = "
						SELECT 
							* 
						FROM 
							".SITE_MEMBER_TBL." 
						WHERE 
							MATCH(membername) AGAINST (:query) 
						LIMIT 1
						";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':query', str_replace(" ", "", $query));
				$statement->execute();
				$member_result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if (count($member_result) > 0) {
					return $member_result;
				} else {
					return null;
				}
				
			}
		}

		public function getAddonFiltered($cat, $order = null, $page = 1, $query = null)
		{
			global $connection, $addon_view_range;

			if ($order != null) {
				if ($order == "oldest") {
					$order_type = "ASC";
				} else {
					$order_type = "DESC";
				}
			} else {
				$order_type = "DESC";
			}

			$offset = (($page-1) * $addon_view_range);

			if (databaseConnection()) {
				try {
					if ($cat == null || $cat == "all") {
						if ($query == null) {
							$sql = "
									SELECT 
										ID_ADDON, ID_AUTHOR, COLOR_ID, addon_title, addon_type, thumbnail, is_beta, status, membername 
									FROM 
										".SITE_ADDON." 
											LEFT JOIN 
										".SITE_MEMBER_TBL." 
											on 
										".SITE_ADDON.".ID_AUTHOR = ".SITE_MEMBER_TBL.".ID_MEMBER 
									ORDER BY 
										ID_ADDON {$order_type} 
									LIMIT 
										{$addon_view_range} 
									OFFSET 
										{$offset} 
									";
							$statement = $connection->prepare($sql);
						} else {
							//Check if the search query contains author name, if true then search by author
							if (strpos(strtolower($query), "author:") !== false) {
								$member_result = $this->getMemberIdByName($query);
								$sql = "
										SELECT 
											ID_ADDON, ID_AUTHOR, COLOR_ID, addon_title, addon_type, thumbnail, is_beta, status, membername 
										FROM 
											".SITE_ADDON." 
												LEFT JOIN 
											".SITE_MEMBER_TBL." 
												on 
											".SITE_ADDON.".ID_AUTHOR = ".SITE_MEMBER_TBL.".ID_MEMBER 
										WHERE 
											ID_AUTHOR = {$member_result[0]['ID_MEMBER']} 
										ORDER BY 
											ID_ADDON {$order_type} 
										LIMIT 
											{$addon_view_range} 
										OFFSET 
											{$offset}
										";
								$statement = $connection->prepare($sql);
								$statement->bindValue(':query', $query);
							} else {
								$sql = "
										SELECT 
											ID_ADDON, ID_AUTHOR, COLOR_ID, addon_title, addon_type, thumbnail, is_beta, status, membername 
										FROM 
											".SITE_ADDON." 
												LEFT JOIN 
											".SITE_MEMBER_TBL." 
												on 
											".SITE_ADDON.".ID_AUTHOR = ".SITE_MEMBER_TBL.".ID_MEMBER 
										WHERE 
											MATCH(tags,addon_title,short_description,readme_content,addon_type) AGAINST (:query) 
										ORDER BY 
											ID_ADDON {$order_type} 
										LIMIT 
											{$addon_view_range} 
										OFFSET 
											{$offset}
										";
								$statement = $connection->prepare($sql);
								$statement->bindValue(':query', $query);
							}
						}
					} else {
						if ($query == null) {
							$sql = "
								SELECT 
									ID_ADDON, ID_AUTHOR, COLOR_ID, addon_title, addon_type, thumbnail, is_beta, status, membername 
								FROM 
									".SITE_ADDON." 
										LEFT JOIN 
									".SITE_MEMBER_TBL." 
										on 
									".SITE_ADDON.".ID_AUTHOR = ".SITE_MEMBER_TBL.".ID_MEMBER 
								WHERE 
									addon_type = :cat 
								ORDER BY 
									ID_ADDON ".$order_type." 
								LIMIT 
									".$addon_view_range." 
								OFFSET 
									".(($page-1) * $addon_view_range);

							$statement = $connection->prepare($sql);
							$statement->bindValue(':cat', $cat);
						} else {
							//Check if the search query contains author name, if true then search by author
							if (strpos(strtolower($query), "author:") !== false) {
								$member_result = $this->getMemberIdByName($query);
								$sql = "
										SELECT 
											ID_ADDON, ID_AUTHOR, COLOR_ID, addon_title, addon_type, thumbnail, is_beta, status, membername 
										FROM 
											".SITE_ADDON." 
												LEFT JOIN 
											".SITE_MEMBER_TBL." 
												on 
											".SITE_ADDON.".ID_AUTHOR = ".SITE_MEMBER_TBL.".ID_MEMBER 
										WHERE 
											ID_AUTHOR = {$member_result[0]['ID_MEMBER']} 
											AND 
											addon_type = :cat 
										ORDER BY 
											ID_ADDON {$order_type} 
										LIMIT 
											{$addon_view_range} 
										OFFSET 
											{$offset}
										";
								$statement = $connection->prepare($sql);
								$statement->bindValue(':cat', $cat);
							} else {
								$sql = "
									SELECT 
										ID_ADDON, ID_AUTHOR, COLOR_ID, addon_title, addon_type, thumbnail, is_beta, status, membername 
									FROM 
										".SITE_ADDON." 
											LEFT JOIN 
										".SITE_MEMBER_TBL." 
											on 
										".SITE_ADDON.".ID_AUTHOR = ".SITE_MEMBER_TBL.".ID_MEMBER 
									WHERE 
										addon_type = :cat 
										AND 
										MATCH(tags,addon_title,short_description,readme_content,addon_type) AGAINST (:query) 
									ORDER BY 
										ID_ADDON ".$order_type." 
									LIMIT 
										".$addon_view_range." 
									OFFSET 
										".(($page-1) * $addon_view_range);

								$statement = $connection->prepare($sql);
								$statement->bindValue(':cat', $cat);
								$statement->bindValue(':query', $query);
							}
						}
					}
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					if (count($result) > 0) {
						return $result;
					}
				} catch (Exception $e) {
					return $e;
				}
			}
		}

		public function getAddonCount($cat = null, $query = null)
		{
			global $connection;
			if (databaseConnection()) {
				try {
					if ($cat == null || $cat == "all") {
						if ($query == null) {
							$sql = "SELECT ID_ADDON FROM " . SITE_ADDON;
							$statement = $connection->prepare($sql);
						} else {
							//Check if the search query contains author name, if true then search by author
							if (strpos(strtolower($query), "author:") !== false) {
								$member_result = $this->getMemberIdByName($query);
								$sql = "SELECT ID_ADDON FROM " . SITE_ADDON . " WHERE ID_AUTHOR = {$member_result[0]['ID_MEMBER']}";
								$statement = $connection->prepare($sql);
							} else {
								$sql = "SELECT ID_ADDON FROM " . SITE_ADDON . " WHERE MATCH(tags,addon_title,short_description,readme_content,addon_type) AGAINST (:query)";
								$statement = $connection->prepare($sql);
								$statement->bindValue(':query', $query);
							}
						}
					} else {
						if ($query == null) {
							$sql = "SELECT ID_ADDON FROM " . SITE_ADDON . " WHERE addon_type = :cat";
							$statement = $connection->prepare($sql);
							$statement->bindValue(':cat', $cat);
						} else {
							//Check if the search query contains author name, if true then search by author
							if (strpos(strtolower($query), "author:") !== false) {
								$member_result = $this->getMemberIdByName($query);
								$sql = "SELECT ID_ADDON FROM " . SITE_ADDON . " WHERE ID_AUTHOR = {$member_result[0]['ID_MEMBER']} AND addon_type = :cat";
								$statement = $connection->prepare($sql);
								$statement->bindValue(':cat', $cat);
							} else {
								$sql = "SELECT ID_ADDON FROM " . SITE_ADDON . " WHERE addon_type = :cat AND MATCH(tags,addon_title,short_description,readme_content,addon_type) AGAINST (:query)";
								$statement = $connection->prepare($sql);
								$statement->bindValue(':query', $query);
								$statement->bindValue(':cat', $cat);
							}
						}
					}
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					return count($result);

				} catch (Exception $e) {
					return $e;
				}
			}
		}
	}
