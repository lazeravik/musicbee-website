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

$no_guests = true; //kick off the guests
require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';
require_once $siteRoot.'setting.php';
if(!@$_SERVER['HTTP_REFERER']) die('No direct Access');
//var_dump($_POST);
if(!isset($_POST['target'])) die('{"status": "0", "data": "'.$lang['412'].'"}');


if ($_POST['target']=="imgur") {

	if (!IMGUR_UPLOAD_ON) {
		die('{"status": "0", "data": "'.$lang['413'].'"}');
	}

	$img = $_FILES['img'];
	if($img['name']=='' || $img['size'] <= 2){  
		echo '{"status": "0", "data": "'.$lang['414'].'"}';
		exit();
	}
	if ($img['size'] > 2097152) {
		echo '{"status": "0", "data": "'.$lang['415'].'"}';
		exit();
	}


	//var_dump($img);
	$client_id = IMGUR_CLIENT_ID;
	$filename = $img['tmp_name'];
	$handle = fopen($filename, "r");
	$data = fread($handle, filesize($filename));
	$pvars   = array('image' => base64_encode($data));
	$headers = array("Authorization: Client-ID $client_id");
	$url = 'https://api.imgur.com/3/image.json';


	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL=> $url,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_POST => 1,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POSTFIELDS => $pvars
		));
	$out = curl_exec($curl);
	curl_close ($curl);
	$pms = json_decode($out,true);
	$url=$pms['data']['link'];

	if($url!=""){
		echo '{"status": "1", "url": "'.$url.'", "data": "'.$lang['416'].'"}';
	}else{
		echo '{"status": "0", "data": "'.$lang['417'].$pms['data']['error'].'"}';
		exit();
	} 

} elseif ($_POST['target']=="mediafire") {
	if (!MEDIAFIRE_UPLOAD_ON) {
		die('{"status": "0", "data": "'.$lang['418'].'"}');
	}
	if (MF_APP_ID == "" || MF_API_KEY == "" || MF_EMAIL == "" || MF_PASS == "")
	{
		die('{
			"status": "0", 
			"data": "'.$lang['419'].'"
		}');
	}

	$file = $_FILES['file'];
	$supported_ext = array("rar", "zip", "7z", "tgz");
	$file_parts = pathinfo($file["name"]);

	//check file validity by it's name and size
	if($file['name']=='' || $file['size'] <= 1){  
		echo '{"status": "0", "data": "'.$lang['420'].'"}';
		exit();
	}

	//maximum 4MB file allowed!
	if ($file['size'] > 4194304) {
		echo '{"status": "0", "data": "'.$lang['421'].'"}';
		exit();
	}
	
	//is the file extension valid
	if (!in_array($file_parts['extension'], $supported_ext)){
		echo '{"status": "0", "data": "'.$lang['422'].'"}';
		exit();
	}
	  /*
	 * Move uploaded file to current script folder and start uploading
	 */
	  $uploadedFile = $siteRoot."temp/" . basename($file["name"]);

	  if (move_uploaded_file($file["tmp_name"], $uploadedFile))
	  {
		/*
		 * Initilize a new instance of the class
		 */
		include($siteRoot."includes/mediafire/mflib.php");

		$mflib = new mflib(MF_APP_ID, MF_API_KEY);
		$mflib->email = MF_EMAIL;
		$mflib->password = MF_PASS;

		/*
		 * Select a file to be uploaded. The third argurment of method fileUpload() 
		 * is the quickkey of the destination folder. In this case it's omitted, which 
		 * means the file will be stored in the root folder - 'My files'
		 */
		$sessionToken = $mflib->userGetSessionToken(null);
		$uploadKey = $mflib->fileUpload($sessionToken, $uploadedFile);
	}

	 deleteFile(basename($file["name"]));

	/*
	 * Print the upload result
	 */
	if ($uploadKey)
	{
		// Keep polling until we get status 99
		do {
        sleep(5);
        $result = $mflib->filePollUpload($sessionToken, $uploadKey);
    	} while ($result["status"] != 99);

		//var_dump($result);
		if ($result['result_message']=="Success") {
			if ($result['status']=="99") {
				echo '{
					"status": "1", 
					"url": "http://www.mediafire.com/download/'.$result["quickkey"].'/'.$result["filename"].'", 
					"size": "'.$result["size"].'", 
					"quickkey": "'.$result["quickkey"].'", 
					"data": "'.$lang['423'].'"
				}';
			} elseif ($result['status']=="5") {
				echo '{
					"status": "0", 
					"data": "'.$lang['424'].$result["description"].'"
				}';
				exit();
			} else {
				echo '{
					"status": "0", 
					"data": "'.$lang['425'].$result["description"].'"
				}';
				exit();
			}

		} else {
			echo '{
				"status": "0", 
				"data": "'.$lang['417'].$result["fileerror_messagefileerror_message"].'"
			}';
			exit();
		}

	}

}
function deleteFile($filename)
{
	global $siteRoot;
	if (file_exists($siteRoot."temp/".$filename)) {
		unlink($siteRoot."temp/".$filename);
	}
}
?>