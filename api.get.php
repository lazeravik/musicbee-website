<?php
/**
* @author Avik B
* @version 1.0
*
* We will be taking the get request and make fetch the data from the database directly.
* This way data will be fresh and upto date, in the previous we were creating json file on the update
* But now we don't create a static json file, we generate one.
*
* This approach reduces the needs of writing permission on the directory.
*
* THIS ONLY ALLOWS PUBLIC INFORMATION. NO PROTECTED INFO CAN BE ACCESSED.
*
*/



if (isset($_GET['type']) && isset($_GET['action'])) {
	include './setting.php'; //get the server settings
	$type = $_GET['type']; //cache the request data type to a variable
	$action = $_GET['action']; //action is the request itself

	//if the request is for JSON then intialize json parsing
	if ($type=="json") {

		//switch between actions
		switch ($action) {
			case 'release-info':
				printJson(json_encode(getReleaseInfo())); //encode the json
				break;
			
			default:
				# code...
				break;
		}
	}
}

function printJson($encodedData)
{
	print_r($encodedData); //this prints the data to a human eye
}

function getReleaseInfo()
{
	global $connection;
	if (databaseConnection()) {
		try {
			$sql = "SELECT * FROM ".SITE_MB_CURRENT_VERSION_TBL;
			$statement = $connection->prepare($sql);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) > 0) {
				return $result; //Get the availablity first 1= available, 0=already disabled
			} else {
				return $lang['AP_NO_RECORD']; //store the error message in the variable
			}
		} catch (Exception $e) {
			return "Something went wrong. ".$e; //store the error message in the variable
		}
	}
}

/**
* Checks if database connection is opened. If not, then this method tries to open it.
* @return bool Success status of the database connecting process
*/
	function databaseConnection()
	{
		global $connection;
	// if connection already exists
		if ($connection != null) {
			return true;
		} else {
			try {
				$connection = new PDO('mysql:host='. DB_HOST .';dbname='. SITE_DB_NAME . ';charset=utf8', SITE_DB_USER, SITE_DB_PASS);
				return true;
			} catch (PDOException $e) {}
		}
	// default return
		return false;
	}
?>