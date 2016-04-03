<?php
/**
* Store and gets download statistics for addons and musicbee downloads
*/
class Stats
{
	private $addon_tbl = SITE_ADDON;
	private $member_tbl = SITE_MEMBER_TBL;
	private $likes_tbl = SITE_ADDON_LIKE;
	private $download_stat_tbl = SITE_DOWNLOAD_STAT;
	private $settings_tbl = SETTINGS;


	public function addStat($stat)
	{
		global $connection;

		if ($this->checkStatExistsByIp($stat) == false) {
			if (databaseConnection()) {
				try {
					$sql = "INSERT
					INTO ".SITE_DOWNLOAD_STAT."
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
		global $connection;

		if (databaseConnection()) {
			try {
				$sql = "SELECT STAT_ID 
				FROM ".SITE_DOWNLOAD_STAT." 
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
	}








}