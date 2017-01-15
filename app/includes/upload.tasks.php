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

	$no_guests = true; //kick off the guests
	$no_directaccess = true;
	require_once $_SERVER['DOCUMENT_ROOT'] . '/app/functions.php';
	require_once $link['root'] . 'setting.php';

	if (!isset($_POST['target']))
		die('{"status": "0", "data": "' . $lang['412'] . '"}');

	if ($_POST['target'] == "imgur") {

		if (!$setting['imgurUploadOn']) {
			die('{"status": "0", "data": "' . $lang['413'] . '"}');
		}

		$img = $_FILES['img'];

		//Check image file name and size if empty or size is less that 2byte DIE!
		if ($img['name'] == '' || $img['size'] <= 2) {
			die('{"status": "0", "data": "' . $lang['414'] . '"}');
		}

		//Check the image size, if it is larger than 2MB DIE!
		if ($img['size'] > 2097152) {
			die('{"status": "0", "data": "' . $lang['415'] . '"}');
		}


		//var_dump($img);
		$client_id = $setting['imgurClientID'];
		$filename = $img['tmp_name'];
		$handle = fopen($filename, "r");
		$data = fread($handle, filesize($filename));
		$pvars = array('image' => base64_encode($data));
		$headers = array("Authorization: Client-ID $client_id");
		$url = 'https://api.imgur.com/3/image.json';
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL            => $url,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_POST           => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_HTTPHEADER     => $headers,
			CURLOPT_POSTFIELDS     => $pvars,
		));
		$out = curl_exec($curl);
		curl_close($curl);
		$pms = json_decode($out, true);
		$url = $pms['data']['link'];

		if ($url != "") {
			exit('{"status": "1", "url": "' . $url . '", "data": "' . $lang['416'] . '", "callback_function": "uploadsuccess"}');
		} else {
			die('{"status": "0", "data": "' . $lang['417'] . $pms['data']['error'] . '"}');
		}

	}
