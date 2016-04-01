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

include_once $_SERVER['DOCUMENT_ROOT'].'functions.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $lang['197']; ?></title>
	<!--include common meta tags and stylesheets -->
	<?php include $link['root'].'includes/meta&styles.php'; ?>
	<!-- <link rel="stylesheet" type="text/css" href="<?php echo $link['url']; ?>styles/404.css"> -->
	<!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
	<?php include $link['root'].'includes/font.helper.php'; ?>
</head>
<body>
<!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
<?php
include($mainmenu);
?>

<!-- BODY CONTENT -->
<div id="main">
	<div id="main_panel">

		<div class="mb_landing align_right">
			<div class="sub_content" id="simple_powerful">
				<div class="hero_text_top">
					<h1><?php echo $lang['194']; ?></h1>
					<h2><?php echo $lang['195']; ?></h2>
					<br/>
					<p><?php echo $lang['196']; ?></p>
					<br/>
					<br/>
					<hr class="line"/>
					<a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>" class="btn btn_green"><?php echo $lang['198']; ?></a>
				</div>
			</div>
		</div>

	</div>
</div>
<!--IMPORTANT-->
<!-- INCLUDE THE FOOTER AT THE END -->
<?php
include($footer);
?>
<script src="<?php echo $link['url']; ?>scripts/jquery-2.1.4.min.js"></script>
</body>
</html>