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

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $lang['bug_title']; ?></title>
	<meta name="description" content="<?php echo $lang['bug_desc']; ?>">

	<!--include common meta tags and stylesheets -->
	<?php include $link['root'].'/includes/meta&styles.php'; ?>

	<!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
	<?php include $link['root'].'/includes/font.helper.php'; ?>
</head>
<body>

<!--IMPORTANT-->
<!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
<?php
include($mainmenu);
?>

<!-- BODY CONTENT -->
<div id="main">
	<div id="main_panel">
		<div class="main_content_wrapper col_2">
			<div class="sub_content_wrapper">
				<div class="box_content">
					<div class="show_info custom">
						<h3><?php echo $lang['bug_1']; ?></h3>
						<p class="description"><?php echo $lang['bug_2']; ?></p>
					</div>
					<ul class="list">
						<?php echo $lang['bug_3']; ?>
					</ul>
					<hr class="line"/>
					<ul class="list">
						<p>
							<?php echo $lang['bug_4']; ?>
						</p>
						<a href="<?php echo $setting['musicbeeBugLink']; ?>" class="btn btn_blue">
							<?php echo $lang['bug_button_1']; ?>
						</a>
					</ul>

				</div>
			</div>
			<div class="sub_content_wrapper">
				<div class="box_content">
					<div class="show_info info_darkgrey custom">
						<h3><?php echo $lang['bug_5']; ?></h3>
						<p class="description"><?php echo $lang['bug_6']; ?></p>
					</div>
					<ul class="list">
						<?php echo $lang['bug_7']; ?>
					</ul>
					<hr class="line"/>
					<ul class="list">
						<p><?php echo $lang['bug_8']; ?></p>
						<a href="<?php echo $setting['websiteBugLink']; ?>" class="btn btn_blue">
							<?php echo $lang['bug_9']; ?>
						</a>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="space medium"></div>
<!--IMPORTANT-->
<!-- INCLUDE THE FOOTER AT THE END -->
<?php
include($footer);
?>
</body>
</html>