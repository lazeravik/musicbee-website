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

//Start a new session if no session detected..... WARNING! IT REQUIRES PHP 5.4 OR LATER
if(session_status() == PHP_SESSION_NONE) {
	session_start();
}
include_once dirname(__FILE__) . '/classes/Paths.php';
//Language array
$lang = array();
$language;
require_once $link['root'].'classes/Language.php';
new Language();
	
	if(isset($_GET['code']))
	{
		if($_GET['code'] == '102'){
			$h = $lang['need_login_first'];
			$desc = $lang['need_login_first_desc'];
		}else if($_GET['code'] == '101'){
			$h = $lang['not_allowed'];
			$desc = $lang['not_allowed_desc'];
		}else if($_GET['code'] == '103'){
			$h = $lang['no_forum_intg'];
			$desc = $lang['no_forum_intg_desc'];
		}else if($_GET['code'] == '104'){
			$h = $lang['not_found_err'];
			$desc = $lang['not_found_err_desc'];
		}else if($_GET['code'] == '105'){
			$h = $lang['no_direct_access'];
			$desc = $lang['no_direct_access_desc'];
		}else if($_GET['code'] == '106'){
			$h = $lang['not_allowed'];
			$desc = $lang['not_allowed_desc'];
		} else {
			$h = $lang['no_err_code_define'];
			$desc = $lang['no_err_code_define_desc'];
		}
	}
	else
	{
		$h = $lang['no_err_code_define'];
		$desc = $lang['no_err_code_define_desc'];
	}

$errorcode = (isset($_GET['code']))? htmlspecialchars(trim($_GET['code'])):'010';
$styleDir = GetStyleDir();
$html = <<<HTML
<!doctype html>
<html>
	<head>
		<title>KB $errorcode | $h</title>
		<link rel="stylesheet" type="text/css" href="{$styleDir}dist/mb_main.css?1.2.1">
		<link rel="stylesheet" href="{$styleDir}font-awesome.min.css">
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
					<a href="{$link['url']}" class="btn btn_green">Go to home &nbsp;<i class="fa fa-arrow-right"></i></a>
					<a href="{$link['login']}" class="btn btn_green">Login &nbsp;<i class="fa fa-sign-in"></i></a>

					<a href="{$link['forum']}?action=register" class="btn btn_green">Create a new account</a>
				</div>
			</div>
		</div>
	</div>
	</body>
</html>
HTML;

echo ($html);