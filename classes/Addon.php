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

	public function getAddonFiltered($cat, $order = "DESC", $query = null, $status = "1") {
		global $connection;

		$order = ($order == "oldest") ? "ASC" : "DESC";

		if (databaseConnection ()) {
			$addon_tbl = SITE_ADDON;
			$member_tbl = SITE_MEMBER_TBL;

			try {
				if ($cat == null || $cat == "all") {
					if ($query == null) {
						$sql = <<<SQL
						SELECT ID_ADDON, ID_AUTHOR, addon_title, addon_type, thumbnail, is_beta, status, membername
							FROM $addon_tbl
							LEFT JOIN $member_tbl
							on $addon_tbl.ID_AUTHOR =  $member_tbl.ID_MEMBER
							WHERE status IN ($status)
							ORDER BY ID_ADDON $order;
SQL;
						$statement = $connection->prepare ($sql);
						$statement->execute ();
						$result = $statement->fetchAll (PDO::FETCH_ASSOC);
						if (count ($result) > 0) {
							return $result;
						}
					} else {
						//Check if the search query contains author name, if true then search by author
						if (strpos (strtolower ($query), "author") !== false && strpos ($query, ":") !== false) {
							//declare the $result as an array, otherwise it will throw an error for the first merging with an array
							$result = array();

							//search for member id that matches this member name
							$member_result = $this->getMemberIdByName ($query);

							//If the search returns 1 or more result then loop through them and get addons by thses members and merge them in an array
							if (count ($member_result) > 0) {
								foreach ($member_result as $key => $member_val) {
									$sql = <<<SQL
									SELECT ID_ADDON, ID_AUTHOR, addon_title, addon_type, thumbnail, is_beta, status, membername
									FROM $addon_tbl
									LEFT JOIN $member_tbl
									on $addon_tbl.ID_AUTHOR = $member_tbl.ID_MEMBER
									WHERE ID_AUTHOR = {$member_val['ID_MEMBER']}
										  AND
										  status IN ($status)
									ORDER BY
										  ID_ADDON $order;
SQL;
									$statement = $connection->prepare ($sql);
									$statement->execute ();
									$result_array = $statement->fetchAll (PDO::FETCH_ASSOC);
									//merge the result array with the previous result for each loop
									$result = array_merge ($result_array, $result);
								}
								if (count ($result) > 0) {
									//randomize the order, we don't want all the pldest addon author to appear at the top
									shuffle ($result);

									return $result;
								}

								return null;
							}

							//return null if the search didn't returned any result
							return null;
						} else {
							$sql = <<<SQL
							SELECT ID_ADDON, ID_AUTHOR, addon_title, addon_type, thumbnail, is_beta, status, membername
							FROM $addon_tbl
							LEFT JOIN $member_tbl
							on $addon_tbl.ID_AUTHOR = $member_tbl.ID_MEMBER
							WHERE
								MATCH(tags,addon_title,short_description,readme_content,addon_type) AGAINST (:query)
								AND
								status IN ($status)
							ORDER BY
								ID_ADDON $order;
SQL;
							$statement = $connection->prepare ($sql);
							$statement->bindValue (':query', $query);
							$statement->execute ();
							$result = $statement->fetchAll (PDO::FETCH_ASSOC);
							if (count ($result) > 0) {
								return $result;
							}

							return null;
						}
					}
				} else {
					if ($query == null) {
						$sql = <<<SQL
								SELECT
									ID_ADDON, ID_AUTHOR, addon_title, addon_type, thumbnail, is_beta, status, membername
								FROM
									$addon_tbl
										LEFT JOIN
									$member_tbl
										on
									$addon_tbl.ID_AUTHOR = $member_tbl.ID_MEMBER
								WHERE
									addon_type IN (:cat)
									AND
									status IN ($status)
								ORDER BY
									ID_ADDON $order;
SQL;

						$statement = $connection->prepare ($sql);
						$statement->bindValue (':cat', $cat);
						$statement->execute ();
						$result = $statement->fetchAll (PDO::FETCH_ASSOC);
						if (count ($result) > 0) {
							return $result;
						}

						return null;
					} else {
						//Check if the search query contains author name, if true then search by author
						if (strpos (strtolower ($query), "author") !== false && strpos ($query, ":") !== false) {

							//declare the $result as an array, otherwise it will throw an error for the first merging with an array
							$result = array();

							//search for member id that matches this member name
							$member_result = $this->getMemberIdByName ($query);

							//If the search returns 1 or more result then loop through them and get addons by thses members and merge them in an array
							if (count ($member_result) > 0) {
								foreach ($member_result as $key => $member_val) {
									$sql = <<<SQL
												SELECT
													ID_ADDON, ID_AUTHOR, addon_title, addon_type, thumbnail, is_beta, status, membername
												FROM
													$addon_tbl
														LEFT JOIN
													$member_tbl
														on
													$addon_tbl.ID_AUTHOR = $member_tbl.ID_MEMBER
												WHERE
													ID_AUTHOR = {$member_val['ID_MEMBER']}
													AND
													addon_type = :cat
													AND
													status IN ($status)
												ORDER BY
													ID_ADDON $order;
SQL;
									$statement = $connection->prepare ($sql);
									$statement->bindValue (':cat', $cat);
									$statement->execute ();
									$result_array = $statement->fetchAll (PDO::FETCH_ASSOC);

									//merge the result array with the previous result for each loop
									$result = array_merge ($result_array, $result);
								}

								if (count ($result) > 0) {
									//randomize the order, we don't want all the pldest addon author to appear at the top
									shuffle ($result);

									return $result;
								}

								return null;
							}

							//return null if the search didn't returned any result
							return null;
						} else {
							$sql = <<<SQL
									SELECT
										ID_ADDON, ID_AUTHOR, addon_title, addon_type, thumbnail, is_beta, status, membername
									FROM
										$addon_tbl
											LEFT JOIN
										$member_tbl
											on
										$addon_tbl.ID_AUTHOR = $member_tbl.ID_MEMBER
									WHERE
										status IN ($status)
										AND
										addon_type = :cat
										AND
										MATCH(tags,addon_title,short_description,readme_content,addon_type) AGAINST (:query)
									ORDER BY
										ID_ADDON $order;
SQL;

							$statement = $connection->prepare ($sql);
							$statement->bindValue (':cat', $cat);
							$statement->bindValue (':query', $query);
							$statement->execute ();
							$result = $statement->fetchAll (PDO::FETCH_ASSOC);
							if (count ($result) > 0) {
								return $result;
							}

							return null;
						}
					}
				}
			} catch (Exception $e) {
				return $e;
			}
		}
	}

	public function getMemberIdByName($query) {
		global $connection;
		if (databaseConnection ()) {
			//remove the :author
			$query = str_replace (array("author",":",), array("","",), $query) . "*";
			$sql = "
						SELECT
							*
						FROM
							" . SITE_MEMBER_TBL . "
						WHERE
							MATCH(membername) AGAINST (:query IN BOOLEAN MODE)
						LIMIT 10
						";
			$statement = $connection->prepare ($sql);
			$statement->bindValue (':query', str_replace (" ", "", $query));
			$statement->execute ();
			$member_result = $statement->fetchAll (PDO::FETCH_ASSOC);
			if (count ($member_result) > 0) {
				return $member_result;
			} else {
				return null;
			}
		}
	}

}
