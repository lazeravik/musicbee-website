<?php
/**
 * Copyright (c) AvikB, some rights reserved.
 * Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 * Spelling mistakes and fixes from community members.
 */

//Start a new session if no session detected..... WARNING! IT REQUIRES PHP 5.4 OR LATER
if(session_status() == PHP_SESSION_NONE) {
	session_start();
}
	
	if(!isset($_GET['code'])) {
		$h = 'No Error code defined!';
		$desc = 'Something is terribly wrong! No error code detected!';
	}else if($_GET['code'] == '102'){
		$h = 'You need to login first!';
		$desc = 'Hey there! you are not logged in. If you don\'t have an account go ahead and create one';
	}else if($_GET['code'] == '101'){
		$h = 'You are not allowed';
		$desc = 'You don\'t have enough permission to view/modify the content. Only Admins are allowed.';
	}else if($_GET['code'] == '103'){
		$h = 'No forum integration detected';
		$desc = 'This website requires SMF forum integration to work properly. Forum must be in <code>www.website.com/forum/</code> directory.<br/> You can change the forum directory by modifying <code>functions.php</code> file and change the <code>SSI.php</code> file location';
	}else if($_GET['code'] == '104'){
		$h = 'Not Found';
		$desc = 'Can not found what you are looking for!';
	}else if($_GET['code'] == '105'){
		$h = 'Direct access not allowed';
		$desc = 'No direct access is allowed.';
	}else if($_GET['code'] == '106'){
		$h = 'You are not allowed';
		$desc = 'You don\'t have enough permission to view/modify the content. Only Mods are allowed.';
	}


$errorcode = (isset($_GET['code']))?$_GET['code']:'010';
$url = 'http://'.$_SERVER['HTTP_HOST'];
$html = <<<EOT
<!doctype html>
<html>
	<head>
		<title>KB $errorcode | $h</title>
		<link rel="stylesheet" type="text/css" href="../styles/dist/mb_main.css?1.2.1">
		<link rel="stylesheet" href="../styles/font-awesome.min.css">
	</head>
	<body>
	<div id="main_panel">

<div class="mb_landing align_right">
			<div class="sub_content">
				<div class="hero_text_top">
					<h1>$h</h1>
					<h2>ERROR-$errorcode</h2>
					
					<br/>
					<p>$desc</p>
					<br/>
					<br/>
					<hr class="line"/>
					<a href="$url" class="btn btn_green">Go to home &nbsp;<i class="fa fa-arrow-right"></i></a>
					<a href="$url/forum/?action=login" class="btn btn_green">Login &nbsp;<i class="fa fa-sign-in"></i></a>

					<a href="$url/forum/?action=register" class="btn btn_green">Create a new account</a>
				</div>
			</div>
		</div>
	</div>
	</body>
</html>
EOT;

echo ($html);