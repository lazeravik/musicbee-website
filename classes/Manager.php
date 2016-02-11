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
	 * Handles Admin-Panel(musicbee website info update) related stuff
	 * @author : AvikB;
	 */
	class Manager
	{
		public $errorMessage;

		private $db_connection = null;

		public function saveArchieveData($note_html)
		{
			global $connection;
			if ($_POST['save']) {
				//validate appname, mbversion, and supported os from user input
				//We wan't to add the data to all version table(this is the version archieve) first.
				if ($this->validateData($_POST['appname'], $_POST['ver'], $_POST['os'])) {
					/* Cache the POST variiables */
					$appname = $this->safeInput($_POST['appname']);
					$ver = $this->removeSpace($this->safeInput($_POST['ver']));
					$os = $this->safeInput($_POST['os']);
					$ilink1 = (isset($_POST['ilink1'])) ? $this->removeSpace($this->safeInput($_POST['ilink1'])) : "";
					$ilink2 = (isset($_POST['ilink2'])) ? $this->removeSpace($this->safeInput($_POST['ilink2'])) : "";
					$ilink3 = (isset($_POST['ilink3'])) ? $this->removeSpace($this->safeInput($_POST['ilink3'])) : "";
					$plink1 = (isset($_POST['plink1'])) ? $this->removeSpace($this->safeInput($_POST['plink1'])) : "";
					$note = (isset($_POST['note'])) ? $_POST['note'] : ""; // raw markdown format of the release note
					$note_html = (isset($note_html)) ? $note_html : ""; // converted and purified html format
					$message = (isset($_POST['message'])) ? $this->safeInput($_POST['message']) : "";
					$major = (isset($_POST['major'])) ? 1 : 0;
					$isnew = (isset($_POST['isnew'])) ? true : false; // if the entry is new then we INSERT data, else we UPDATE DATA
					if ($isnew)
						$dashboard = $major; //if the release is major it will be automatically available for addon devs
					else
						$dashboard = (isset($_POST['dashboard'])) ? 1 : 0;
					$date = date("F j, Y"); //current date
					$id_allversion = (isset($_POST['id_allversion'])) ? $_POST['id_allversion'] : "";

					//We only archieve the stable releases
					if ($_POST['save'] == "stable") {
						if (databaseConnection()) {
							try {
								if ($isnew)
									$sql = "INSERT INTO " . SITE_MB_ALL_VERSION_TBL . " SET appname = :appname, version = :version, release_date = :date, supported_os = :os, major = :major, dashboard_availablity = :dashboard, release_note = :note, release_note_html = :note_html";
								else
									$sql = "UPDATE " . SITE_MB_ALL_VERSION_TBL . " SET appname = :appname, version = :version, release_date = :date, supported_os = :os, major = :major, dashboard_availablity = :dashboard, release_note = :note, release_note_html = :note_html WHERE ID_ALLVERSIONS = :id_allversion";
								$statement = $connection->prepare($sql);
								$statement->bindValue(':appname', $appname);
								$statement->bindValue(':version', $ver);
								$statement->bindValue(':os', $os);
								$statement->bindValue(':date', $date);
								$statement->bindValue(':major', $major);
								$statement->bindValue(':dashboard', $dashboard);
								$statement->bindValue(':note', $note);
								$statement->bindValue(':note_html', $note_html);
								if (!$isnew)
									$statement->bindValue(':id_allversion', $id_allversion);
								$statement->execute();
							} catch (Exception $e) {
								$this->errorMessage = $e;

								return false;
							}
						}
					}
					
					if ($_POST['save'] == "stable" && isset($_POST['isCurrent'])) {
						$this->saveOnServerDB(0, 0, $appname, $ver, $os, $ilink1, $ilink2, $ilink3, $plink1, $message, $date);
					} elseif ($_POST['save'] == "beta") {
						$this->saveOnServerDB(1, 1, $appname, $ver, $os, $ilink1, $ilink2, $ilink3, $plink1, $message, $date);
					}

					return true;
				}

				return false; // return false by default
			}
		}

		private function saveOnServerDB($verid, $beta, $appname, $ver, $os, $ilink1, $ilink2, $ilink3, $plink1, $message, $date)
		{
			global $connection;
			//Check if the entry exist in the DB or not, if exist then we want to update it, else create a new entry
			if (databaseConnection()) {
				//Execute the query
				try {
					//Check if the entry exist in the DB or not, if exist then we want to update it, else create a new entry
					if ($this->entryExistDB($verid, "current")) {
						$sql = "UPDATE " . SITE_MB_CURRENT_VERSION_TBL . " SET
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
						$sql = "INSERT INTO " . SITE_MB_CURRENT_VERSION_TBL . " SET
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
				} catch (Exception $e) {
				}

				return false;
			}
		}

		private function entryExistDB($verid, $type)
		{
			global $connection;
			if (databaseConnection()) {
				//Execute the query
				try {
					if ($type == "all")
						$sql = "SELECT ID_ALLVERSIONS FROM " . SITE_MB_ALL_VERSION_TBL . " WHERE ID_ALLVERSIONS = :verid";
					if ($type == "current")
						$sql = "SELECT ID_VERSION FROM " . SITE_MB_CURRENT_VERSION_TBL . " WHERE ID_VERSION = :verid";
					$statement = $connection->prepare($sql);
					$statement->bindValue(':verid', $verid);
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					if (count($result) == 1)
						return true;
					else
						return false;
				} catch (Exception $e) {
				}
			}

			return false;
		}


		public function deleteRecord($record_id)
		{
			global $connection;
			$id = $this->removeSpace($this->safeInput($record_id)); // sanitize the input
			if ($this->entryExistDB($id, "all")) {
				if (databaseConnection()) {
					//Execute the query
					try {
						$sql = "DELETE FROM " . SITE_MB_ALL_VERSION_TBL . " WHERE ID_ALLVERSIONS = :id";
						$statement = $connection->prepare($sql);
						$statement->bindValue(':id', $id);
						$statement->execute();
					} catch (Exception $e) {
						$this->errorMessage = $this->errorMessage . '<p>Can not execute the query! Are we connected to the Database?</p>';

						return false;
					}

					return true;
				}
			} else {
				$this->errorMessage = $this->errorMessage . '<p>The record does not exist!</p>';

				return false;
			}

			return false;
		}

		/** Even though we will sanitize evry bit of the input, this will serve as a basic
		 * notification for the user of what might went wrong, also if they make any accidental mistake
		 *
		 * @param $appname , $ver, $os
		 */
		private function validateData($appname, $ver, $os)
		{
			if (!preg_match("/^[A-Za-z0-9._ ]{1,50}$/", $appname)) {
				$this->errorMessage = $this->errorMessage . ((!empty($this->errorMessage)) ? ' ,' : '') . 'App Name';
			}
			if (!preg_match("/^[A-Za-z0-9. ]{1,50}$/", $ver)) {
				$this->errorMessage = $this->errorMessage . ((!empty($this->errorMessage)) ? ' ,' : '') . 'Version';
			}
			if (!preg_match("/^[\/A-Za-z0-9. ]{1,50}$/", $os)) {
				$this->errorMessage = $this->errorMessage . ((!empty($this->errorMessage)) ? ' ,' : '') . 'Supported OS';
			}
			// If the error message is not empty then generate a Message for the user
			if (!empty($this->errorMessage)) {
				$this->errorMessage = '<p><b>' . $this->errorMessage . '</b> contains some characters that can not be used</p>';

				return false; //data is invalid
			}

			return true;
		}


		/**
		 * @param $value
		 *
		 * @return null|string
		 * Input sanitizer for specialcharacter and html tag escape
		 */
		private function safeInput($value)
		{
			if (!empty($value)) {
				$value = strip_tags($value);
				$value = htmlspecialchars($value);
				$value = str_replace("\\", "", $value);
				$value = str_replace(array("\r\n", "\r", "\n"), "", $value); //remove new line breaks fom the string
				return stripslashes($value);
			}

			return null;
		}

		//remove ebery single spaces from the string
		private function removeSpace($value)
		{
			if (!empty($value)) {
				return str_replace(" ", "", $value);
			}

			return null;
		}

	}
