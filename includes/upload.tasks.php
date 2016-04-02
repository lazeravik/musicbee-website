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
	require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
	require_once $link['root'] . 'setting.php';
	if (!@$_SERVER['HTTP_REFERER']) die('No direct Access');
	if (!isset($_POST['target'])) die('{"status": "0", "data": "' . $lang['412'] . '"}');


	if ($_POST['target'] == "imgur") {

		if (!$setting['imgurUploadOn']) {
			die('{"status": "0", "data": "' . $lang['413'] . '"}');
		}

		$img = $_FILES['img'];
		if ($img['name'] == '' || $img['size'] <= 2) {
			echo '{"status": "0", "data": "' . $lang['414'] . '"}';
			exit();
		}
		if ($img['size'] > 2097152) {
			echo '{"status": "0", "data": "' . $lang['415'] . '"}';
			exit();
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
			echo '{"status": "1", "url": "' . $url . '", "data": "' . $lang['416'] . '"}';
		} else {
			echo '{"status": "0", "data": "' . $lang['417'] . $pms['data']['error'] . '"}';
			exit();
		}

	}