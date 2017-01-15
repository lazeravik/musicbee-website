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
 * Handles Admin-Panel(musicbee website info update) related stuff
 * @author : AvikB;
 */
class Manager
{
	public $errorMessage;

	public function saveArchieveData($note_html) {
		global $connection, $db_info;
		if($_POST['save']) {
			//validate appname, mbversion, and supported os from user input
			//We wan't to add the data to all version table(this is the version archieve) first.
			if($this->validateData($_POST['appname'], $_POST['ver'], $_POST['os'])) {
				/* Cache the POST variiables */
				$appname   = Format::safeInput($_POST['appname']);
				$ver       = Format::removeSpace(Format::safeInput($_POST['ver']));
				$os        = Format::safeInput($_POST['os']);
				$ilink1    = (isset($_POST['ilink1'])) ? Format::removeSpace(Format::safeInput($_POST['ilink1'])) : "";
				$ilink2    = (isset($_POST['ilink2'])) ? Format::removeSpace(Format::safeInput($_POST['ilink2'])) : "";
				$ilink3    = (isset($_POST['ilink3'])) ? Format::removeSpace(Format::safeInput($_POST['ilink3'])) : "";
				$plink1    = (isset($_POST['plink1'])) ? Format::removeSpace(Format::safeInput($_POST['plink1'])) : "";
				$note      = (isset($_POST['note'])) ? $_POST['note'] : ""; // raw markdown format of the release note
				$note_html = (isset($note_html)) ? $note_html : ""; // converted and purified html format
				$message   = (isset($_POST['message'])) ? Format::safeInput($_POST['message']) : "";
				$major     = (isset($_POST['major'])) ? 1 : 0;
				$isnew     = (isset($_POST['isnew'])) ? true : false; // if the entry is new then we INSERT data, else we UPDATE DATA
				if($isnew) {
					$dashboard = $major;
				} //if the release is major it will be automatically available for addon devs
				else {
					$dashboard = (isset($_POST['dashboard'])) ? 1 : 0;
				}
				$date = date("F j, Y"); //current date
				$id_allversion = (isset($_POST['id_allversion'])) ? $_POST['id_allversion'] : "";

				//We only archieve the stable releases
				if($_POST['save'] == "stable") {
					if(databaseConnection()) {
						try {
							if($isnew) {
								$sql = "INSERT INTO {$db_info['mb_all']} SET appname = :appname, version = :version, release_date = :date, supported_os = :os, major = :major, dashboard_availablity = :dashboard, release_note = :note, release_note_html = :note_html";
							} else {
								$sql = "UPDATE {$db_info['mb_all']} SET appname = :appname, version = :version, release_date = :date, supported_os = :os, major = :major, dashboard_availablity = :dashboard, release_note = :note, release_note_html = :note_html WHERE ID_ALLVERSIONS = :id_allversion";
							}
							$statement = $connection->prepare($sql);
							$statement->bindValue(':appname', $appname);
							$statement->bindValue(':version', $ver);
							$statement->bindValue(':os', $os);
							$statement->bindValue(':date', $date);
							$statement->bindValue(':major', $major);
							$statement->bindValue(':dashboard', $dashboard);
							$statement->bindValue(':note', $note);
							$statement->bindValue(':note_html', $note_html);
							if(!$isnew) {
								$statement->bindValue(':id_allversion', $id_allversion);
							}
							$statement->execute();
						} catch(Exception $e) {
							$this->errorMessage = $e;

							return false;
						}
					}
				}

				if($_POST['save'] == "stable" && isset($_POST['isCurrent'])) {
					return $this->saveOnServerDB(0, 0, $appname, $ver, $os, $ilink1, $ilink2, $ilink3, $plink1, $message, $date);
				} elseif($_POST['save'] == "beta") {
					return $this->saveOnServerDB(1, 1, $appname, $ver, $os, $ilink1, $ilink2, $ilink3, $plink1, $message, $date);
				}

				return true;
			}

			return false; // return false by default
		}
	}

	/** Even though we will sanitize evry bit of the input, this will serve as a basic
	 * notification for the user of what might went wrong, also if they make any accidental mistake
	 *
	 * @param $appname , $ver, $os
	 * @param $ver
	 * @param $os
	 *
	 * @return bool
	 */
	private function validateData($appname, $ver, $os)
	{
		global $lang;

		if(!preg_match("/^[A-Za-z0-9._ ]{1,50}$/", $appname)) {
			$this->errorMessage = $this->errorMessage.((!empty($this->errorMessage)) ? ' ,' : '').'App Name';
		}
		if(!preg_match("/^[A-Za-z0-9. ]{1,50}$/", $ver)) {
			$this->errorMessage = $this->errorMessage.((!empty($this->errorMessage)) ? ' ,' : '').'Version';
		}
		if(!preg_match("/^[\/A-Za-z0-9. ]{1,50}$/", $os)) {
			$this->errorMessage = $this->errorMessage.((!empty($this->errorMessage)) ? ' ,' : '').'Supported OS';
		}
		// If the error message is not empty then generate a Message for the user
		if(!empty($this->errorMessage)) {
			$this->errorMessage = '<b>'.$this->errorMessage.'</b> '.$lang['character_cannot_use_error'];

			return false; //data is invalid
		}

		return true;
	}

	private function validateVersion($ver)
	{
		global $lang;

		if(!preg_match("/^[A-Za-z0-9. ]{1,50}$/", $ver)) {
			$this->errorMessage = $this->errorMessage.((!empty($this->errorMessage)) ? ' ,' : '').'Version';
		}

		if(!empty($this->errorMessage)) {
			$this->errorMessage = '<b>'.$this->errorMessage.'</b> '.$lang['character_cannot_use_error'];

			return false; //data is invalid
		}
		return true;
	}


	private function saveOnServerDB($verid, $beta, $appname, $ver, $os, $ilink1, $ilink2, $ilink3, $plink1, $message, $date) {
		global $connection,$db_info;
		//Check if the entry exist in the DB or not, if exist then we want to update it, else create a new entry
		if(databaseConnection()) {
			//Execute the query
			try {
				//Check if the entry exist in the DB or not, if exist then we want to update it, else create a new entry
				if($this->entryExistDB($verid, "current")) {
					$sql = "UPDATE {$db_info['mb_current']} SET
							appname 		= :appname,
							version 		= :version,
							beta 			= :beta,
							release_date 	= :date,
							message 		= :message,
							supported_os 	= :os,
							DownloadLink 	= :DownloadLink,
							MirrorLink1 	= :MirrorLink1,
							MirrorLink2 	= :MirrorLink2,
							PortableLink 	= :PortableLink
							WHERE ID_VERSION = :verid";
				} else {
					$sql = "INSERT INTO {$db_info['mb_current']} SET
							ID_VERSION 		= :verid,
							appname 		= :appname,
							version 		= :version,
							beta 			= :beta,
							release_date 	= :date,
							message 		= :message,
							supported_os 	= :os,
							DownloadLink 	= :DownloadLink,
							MirrorLink1		= :MirrorLink1,
							MirrorLink2 	= :MirrorLink2,
							PortableLink 	= :PortableLink";
				}
				$statement = $connection->prepare($sql);
				$statement->bindValue(':verid', $verid);
				$statement->bindValue(':beta', $beta);
				$statement->bindValue(':appname', $appname);
				$statement->bindValue(':version', $ver);
				$statement->bindValue(':os', $os);
				$statement->bindValue(':date', $date);
				$statement->bindValue(':message', $message);
				$statement->bindValue(':DownloadLink', $ilink1);
				$statement->bindValue(':MirrorLink1', $ilink2);
				$statement->bindValue(':MirrorLink2', $ilink3);
				$statement->bindValue(':PortableLink', $plink1);
				$statement->execute();

				return true;
			} catch(Exception $e) {
				$this->errorMessage = $e;
				return false;
			}
			return false;
		}
	}

	/**
	 * Checks if a MusicBee release entry exists or not.
	 *
	 * @param $verid    <p>MusicBee release version</p>
	 * @param $type     <p>all | current</p>
	 *
	 * @return bool
	 */
	private function entryExistDB($verid, $type) {
		global $connection,$db_info;
		if(databaseConnection()) {
			//Execute the query
			try {
				if($type == "all") {
					$sql = "SELECT ID_ALLVERSIONS FROM {$db_info['mb_all']} WHERE ID_ALLVERSIONS = :verid";
				} else if($type == "current") {
					$sql = "SELECT ID_VERSION FROM {$db_info['mb_current']} WHERE ID_VERSION = :verid";
				}
				$statement = $connection->prepare($sql);
				$statement->bindValue(':verid', $verid);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if(count($result) == 1) {
					return true;
				} else {
					return false;
				}
			} catch(Exception $e) {
			}
		}

		return false;
	}

	/**
	 * Delete saved MusicBee release record
	 *
	 * @param $record_id
	 *
	 * @return bool
	 */
	public function deleteRecord($record_id)
	{
		global $connection, $db_info, $lang;

		$id = Format::removeSpace(Format::safeInput($record_id)); // sanitize the input
		if($this->entryExistDB($id, "all"))
		{
			if(databaseConnection())
			{
				try {
					$sql = "DELETE FROM {$db_info['mb_all']} WHERE ID_ALLVERSIONS = :id";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':id', $id);
					$statement->execute();
				} catch(Exception $e) {
					$this->errorMessage = $e->getMessage();

					return false;
				}

				return true;
			}
		} else {
			$this->errorMessage = $lang['record_not_exist'];

			return false;
		}

		return false;
	}

	/**
	 * Save Patch data to the Database
	 *
	 * @return bool
	 */
	public function savePatch()
	{
		global $lang, $mb, $db_info, $connection;
		if (isset($_POST['ver']) && isset($_POST['link']))
		{
			$patchver = $_POST['ver'];
			$link     = Format::removeSpace(Format::safeInput($_POST['link']));
			$date     = date("F j, Y");

			if ($this->validateVersion($patchver))
			{
				if($patchver > $mb['musicbee_download']['stable']['version'])
				{
					//if a previous patch exists then update else insert
					if($mb['musicbee_download']['patch'] != null)
					{
						$sql = <<< SQL
								UPDATE {$db_info['mb_current']}
								SET
									appname 		= 'NA',
									version 		= :version,
									release_date 	= :date,
									DownloadLink 	= :DownloadLink,
									PortableLink 	= '',
									supported_os 	= 'NA',
									beta 			= 0
								WHERE
									ID_VERSION = 2
SQL;
					}
					else
					{
						$sql = <<<SQL
								INSERT INTO {$db_info['mb_current']}
								SET
									appname 		= 'NA',
									version 		= :version,
									beta 			= 0,
									release_date 	= :date,
									supported_os 	= 'NA',
									DownloadLink 	= :DownloadLink,
									PortableLink 	= '',
									ID_VERSION 		= 2
SQL;
					}
					if(databaseConnection()) {
						try
						{
							$statement = $connection->prepare($sql);
							$statement->bindValue(':version', $patchver);
							$statement->bindValue(':DownloadLink', $link);
							$statement->bindValue(':date', $date);

							$statement->execute();

							return true;
						} catch (Exception $e) {
							$this->errorMessage = $e->getMessage();
							return false;
						}
					}
					return false;
				}
				else
				{
					$this->errorMessage = $this->errorMessage.$lang['ver_patch_desc'];
					return false;
				}
			}
			else
			{
				return false;
			}
		} else {
			$this->errorMessage = $this->errorMessage.$lang['version&link_required'];
			return false;
		}
	}

	/**
	 * Delete MusicBee Patch release
	 *
	 * @return bool
	 */
	public function deletePatch() {
		global $connection,$db_info;

			if(databaseConnection()) {
				try {
					$sql = "DELETE FROM {$db_info['mb_current']} WHERE ID_VERSION = 2";
					$statement = $connection->prepare($sql);
					$statement->execute();

					return true;
				} catch(Exception $e) {
					$this->errorMessage = $this->errorMessage.$e;
					return false;
				}
				return true;
			}
		return false;
	}

	public function compareCurrentRelease($record_id)
	{
		global $connection,$db_info,$mb;

		if(databaseConnection()){
			try
			{
				$sql = "SELECT * FROM {$db_info['mb_all']} WHERE ID_ALLVERSIONS = :id";
				$statement = $connection->prepare($sql);
				$statement->bindValue(':id', $record_id);
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				if(count($result)>0){
					if($result[0]['version']==$mb['musicbee_download']['stable']['version']){
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}

			} catch (Exception $e){
				$this->errorMessage = $this->errorMessage.$e;
				return true;
			}
		}
		return true;
	}


}
