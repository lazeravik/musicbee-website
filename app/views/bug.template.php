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

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $lang['bug_title']; ?></title>
	<meta name="description" content="<?php echo $lang['bug_desc']; ?>">

	<!--include common meta tags and stylesheets -->
	<?php include $link['root'].'includes/meta&styles.php'; ?>

	<!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
	<?php include $link['root'].'includes/font.helper.php'; ?>
</head>
<body>

<!--IMPORTANT-->
<!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
<?php
include($mainmenu);
?>

<!-- BODY CONTENT -->

<div class="top_infobar bug_bg_color" id="top_jump">
	<div class="infobar_wrapper">
		<div class="infobar_inner_wrapper">
			<h2><?php echo $lang['bug_header']; ?></h2>
			<p><?php echo $lang['read_careful_before_bugpost']; ?></p>
		</div>
	</div>
</div>

<div id="main_panel">
	<div class="main_content_wrapper col_2_1">
		<div class="sub_content_wrapper">
			<div class="box_content">
				<div class="show_info info_silver custom">
					<h3><?php echo $lang['mb_related_bug']; ?></h3>
				</div>
				<div class="markdownView box">
					<ul>
						<?php echo $lang['mb_related_bug_description']; ?>
					</ul>
					<hr>
					<a href="<?php echo $setting['musicbeeBugLink']; ?>" class="btn btn_blue">
						<?php echo $lang['mb_bug_create_btn']; ?>
					</a>
				</div>
			</div>

			<div class="box_content">
				<div class="show_info info_silver custom">
					<h3><?php echo $lang['website_related_bug']; ?></h3>
				</div>
				<div class="markdownView box">
					<ul>
						<?php echo $lang['website_related_bug_description']; ?>
					</ul>
					<hr>
					<p><?php echo $lang['bug_post_account_req']; ?></p>
					<a href="<?php echo $setting['websiteBugLink']; ?>" class="btn btn_blue">
						<?php echo $lang['goto_web_discuss_thread']; ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="space small"></div>
<!--IMPORTANT-->
<!-- INCLUDE THE FOOTER AT THE END -->
<?php
include($footer);
?>
</body>
</html>