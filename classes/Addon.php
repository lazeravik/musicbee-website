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
	/**
	 * gets all the addons from this member
	 *
	 * @param int $id user id
	 * @param int $limit
	 *
	 * @return array
	 */
	public function getAddonListByMember($id, $limit) {
		global $connection;
		if (databaseConnection ()) {
			try {
				$sql = "
						SELECT 
							* 
						FROM 
							" . SITE_ADDON . "
								LEFT JOIN 
							" . SITE_MEMBER_TBL . "
								ON
							" . SITE_ADDON . ".ID_AUTHOR = " . SITE_MEMBER_TBL . ".ID_MEMBER
						WHERE 
							ID_AUTHOR = :id AND status = 1
						ORDER BY 
							ID_ADDON DESC 
						LIMIT 
							" . $limit;
				$statement = $connection->prepare ($sql);
				$statement->bindValue (':id', $id);
				$statement->execute ();
				$result = $statement->fetchAll (PDO::FETCH_ASSOC);
				if (count ($result) > 0) {
					return $result;
				} else {
					return null;
				}
			} catch (Exception $e) {

			}
		}
	}


	/**
	 * @param int $id   member id
	 * @param int $limit
	 * @param int $stat addon status code
	 *
	 * @return array
	 */
	public function getAddonListByStatusAndMember($id, $limit, $stat) {
		global $connection;
		if (databaseConnection ()) {
			try {
				$sql = "SELECT * FROM " . SITE_ADDON . " WHERE ID_AUTHOR = :id AND status = " . $stat . " ORDER BY ID_ADDON DESC LIMIT " . $limit;
				$statement = $connection->prepare ($sql);
				$statement->bindValue (':id', $id);
				$statement->execute ();
				$result = $statement->fetchAll (PDO::FETCH_ASSOC);
				if (count ($result) > 0) {
					return $result;
				} else {
					return null;
				}
			} catch (Exception $e) {

			}
		}
	}


	public function getMbVersions($val) {
		global $connection;
		$ver = explode (",", $val); //create an array of supported musicbee versions
		if (databaseConnection ()) {
			foreach ($ver as $versionId) {
				try {
					$sql = "SELECT * FROM " . SITE_MB_ALL_VERSION_TBL . " WHERE ID_ALLVERSIONS = :id";
					$statement = $connection->prepare ($sql);
					$statement->bindValue (':id', $versionId);
					$statement->execute ();
					$result = $statement->fetchAll (PDO::FETCH_ASSOC);
					if (count ($result) > 0) {
						return $result;
					}
				} catch (Exception $e) {
					return null;
				}
			}

			return null;
		}
	}


	public function is_rated($addon_id, $user_id) {
		global $connection;
		if (databaseConnection ()) {
			try {
				$sql = "SELECT * FROM " . SITE_ADDON_LIKE . " WHERE ID_MEMBER = :user_id AND ID_ADDON = :addon_id";
				$statement = $connection->prepare ($sql);
				$statement->bindValue (':user_id', $user_id);
				$statement->bindValue (':addon_id', $addon_id);
				$statement->execute ();
				$result = $statement->fetchAll (PDO::FETCH_ASSOC);
				if (count ($result) > 0) {
					return true;
				} else {
					return false;
				}
			} catch (Exception $e) {

			}
		}
	}


	public function rate($addon_id, $user_id, $rate_val) {
		global $connection;
		if (databaseConnection ()) {
			try {
				if ($rate_val == "like") {
					$sql = "INSERT INTO " . SITE_ADDON_LIKE . " SET ID_MEMBER = :user_id, ID_ADDON = :addon_id";
				} elseif ($rate_val == "unlike") {
					$sql = "DELETE FROM " . SITE_ADDON_LIKE . " WHERE ID_MEMBER = :user_id AND ID_ADDON = :addon_id";
				}
				$statement = $connection->prepare ($sql);
				$statement->bindValue (':user_id', $user_id);
				$statement->bindValue (':addon_id', $addon_id);
				$statement->execute ();

				return true;
			} catch (Exception $e) {

			}
		}
	}


	public function getRating($addon_id, $raw = false) {
		global $connection;
		if (databaseConnection ()) {
			try {
				$sql = "SELECT * FROM " . SITE_ADDON_LIKE . " WHERE ID_ADDON = :id";
				$statement = $connection->prepare ($sql);
				$statement->bindValue (':id', $addon_id);
				$statement->execute ();
				$result = $statement->fetchAll (PDO::FETCH_ASSOC);
				if (count ($result) >= 0) {
					return count ($result);
				}
			} catch (Exception $e) {
				return null;
			}
		}
	}

	public function getAddonInfo($id) {
		global $connection;
		if (databaseConnection ()) {
			try {
				if ($id != null) {
					$sql = "
						SELECT * 
						FROM " . SITE_ADDON . "
								LEFT JOIN 
							" . SITE_MEMBER_TBL . "
								ON
							" . SITE_ADDON . ".ID_AUTHOR = " . SITE_MEMBER_TBL . ".ID_MEMBER
						WHERE 
							ID_ADDON = :id";

					$statement = $connection->prepare ($sql);
					$statement->bindValue (':id', $id);
				}
				$statement->execute ();
				$result = $statement->fetchAll (PDO::FETCH_ASSOC);
				if (count ($result) > 0) {
					return $result;
				}
			} catch (Exception $e) {
				return $e;
			}
		}
	}


}
