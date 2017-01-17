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

$link['url'] = 'http://'.$_SERVER['HTTP_HOST'].'/';

//Get the stored database setting for new connection
require_once 'setting.php';

if(isset($_GET['step'])){
	if($_GET['step']=="final"){
		if(checkConnection()){
			updateData();
		} else {
			die("No Connection");
		}
	}
}


$connection = null;
function checkConnection(){
	global $connection;

	//if connection already exists
	if($connection != null) {
		return true;
	} else {
		try {
			$connection = new PDO('mysql:host='. DB_HOST .';dbname='. SITE_DB_NAME . ';charset=utf8', SITE_DB_USER, SITE_DB_PASS);
			return true;
		} catch(PDOException $e) {
			return false;
		}
	}
}


function updateData(){
	global $connection;

	$db = SITE_DB_NAME;
	$prefix = SITE_DB_PREFIX;

	$sql = <<<SQL

USE {$db};

ALTER TABLE {$prefix}`current_version`
  DROP `appname`,
  DROP `beta`,
  DROP `release_date`,
  DROP `supported_os`;

DELETE FROM {$prefix}help WHERE variable = 'faq_md';
DELETE FROM {$prefix}help WHERE variable = 'faq_html';
INSERT INTO {$prefix}help (`variable`, `data_type`, `data`) VALUES ('help_api_link', 'link', 'http://musicbee.wikia.com/index.php?action=render&title=FAQ');
SQL;

	if(checkConnection()) {
		try {
			$statement = $connection->prepare($sql);
			$statement->execute();

			return true;
		} catch(Exception $e) {
			die($e);
		}
	}
	return false;
}
?>

<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>MusicBee Website Update Setup</title>
	<link rel="stylesheet" href="<?php echo $link['url']; ?>styles/dist/mb_main.css?1.0">

	<script src="<?php echo $link['url']; ?>scripts/jquery-2.1.4.min.js"></script>
</head>
<body>
<div class="top_infobar " id="top_jump">
	<div class="infobar_wrapper">
		<div class="infobar_inner_wrapper">
			<h2>Update Existing Installation</h2>
		</div>
	</div>
</div>

<div class="main_content_wrapper col_2_1">
	<div class="sub_content_wrapper">

		<?php if(isset($_GET['step'])): ?>
			<?php if($_GET['step']=="final"): ?>
				<div class="box_content">
					<span class="show_info custom">
						<h3>Congratualtion</h3>
						<p class="description">Yup, that's it. All Done.</p>
					</span>
				</div>
				<div class="box_content">
					<ul class="list markdownView">
						<h3>Everything should be updated properly.</h3>
						<hr>
						<p class="show_info info_red">Now that installation is complete, please delete <code>update2.php</code> file from the directory.</p>
						<a class="btn btn_blue" href="<?php echo $link['url']; ?>">Go to Home Page</a>
					</ul>

				</div>
				<?php endif; ?>
			<?php else: ?>
				<div class="box_content">
					<span class="show_info custom">
						<h3>Requirements</h3>
						<p class="description">Please make sure the following conditions are met beofre proceeding any further</p>
					</span>
				</div>
				<div class="box_content">
					<div class="markdownView box">
						<h3>Hi there! Welcome to MusicBee Website Update Guide.</h3>
						<p>
							Before you continue any further, make sure you have the following checked.
						</p>
						<hr>
						<p>✅ &nbsp;&nbsp;&nbsp;This will require the website to be installed previously.</p>
					</div>
					<div class="space tiny"></div>
				</div>
				<div class="box_content">

					<?php if(phpversion() > 5.3): ?>
						<ul class="form">
							<button class="btn btn_blue" type="submit" onclick="next()">Continue</button>
						</ul>
					<?php else: ?>
						<p class="show_info info_red custom">You will need atleast PHP 5.4 to continue.</p>
					<?php endif; ?>
				</div>
				<script>
					function next() {
						window.location = "?step=final";
					}
				</script>
			<?php endif; ?>


	</div>


</div>
</body>
</html>

