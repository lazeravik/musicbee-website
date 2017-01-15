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
* Store and gets download statistics for addons and musicbee downloads
*/
class Stats
{

	public function addStat($stat)
	{
		global $connection, $db_info;

		if ($this->checkStatExistsByIp($stat) == false) {
			if (databaseConnection()) {
				try {
					$sql = "INSERT
					INTO {$db_info['download_stat_tbl']}
					SET
					ip_address = :ip,
					is_registered = :is_registered,
					stat_type = :stat_type,
					ID = :id";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':ip', $stat['ip']);
					$statement->bindValue(':is_registered', $stat['is_registered']);
					$statement->bindValue(':stat_type', $stat['stat_type']);
					$statement->bindValue(':id', $stat['id']);
					$statement->execute();
				} catch (Exception $e) {}
			}
		}
	}

	public function checkStatExistsByIp($stat)
	{
		global $connection, $db_info;

		if (databaseConnection()) {
			try {
				$sql = "SELECT STAT_ID 
				FROM {$db_info['download_stat_tbl']}
				WHERE 
				is_registered = {$stat['is_registered']}
				AND
				stat_type = :stat_type 
				AND 
				ip_address = :ip 
				AND 
				ID = :id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':ip', $stat['ip']);
				$statement->bindValue(':stat_type', $stat['stat_type']);
				$statement->bindValue(':id', $stat['id']);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if (count($result) > 0) {
					return true;
				} else {
					return false;
				}
			} catch (Exception $e) {}
		}
		return false;
	}








}