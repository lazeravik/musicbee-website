<?php
/**
* Store and gets download statistics for addons and musicbee downloads
*/
class Stats
{
	
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

	public function getAddonDownloadCountByAuthor($author_id)
	{
		global $connection;

		if (databaseConnection()) {
			try {
				$sql = "SELECT 
							STAT_ID, ip_address,ID_ADDON, ID_AUTHOR 
						FROM ".SITE_DOWNLOAD_STAT." 
								LEFT JOIN 
							".SITE_ADDON." 
								ON 
								".SITE_DOWNLOAD_STAT.".ID = ".SITE_ADDON.".ID_ADDON 
							WHERE
								status = 1
								AND
								ID_AUTHOR = :author_id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':author_id', $author_id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				return count($result);
			} catch (Exception $e) {}
		}
	}

	public function getAddonLikeCountByAuthor($author_id)
	{
		global $connection;

		if (databaseConnection()) {
			try {
				$sql = "SELECT 
							ID_LIKES 
						FROM ".SITE_ADDON_LIKE." 
								LEFT JOIN 
							".SITE_ADDON." 
								ON 
								".SITE_ADDON_LIKE.".ID_ADDON = ".SITE_ADDON.".ID_ADDON 
							WHERE
								status = 1
								AND
								ID_AUTHOR = :author_id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':author_id', $author_id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				return count($result);
			} catch (Exception $e) {}
		}
	}
}