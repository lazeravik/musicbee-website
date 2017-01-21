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
	<title><?php echo $lang['dashboard_title']; ?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo GetStyleDir(); ?>animate.css">

	<!--include common meta tags and stylesheets -->
	<?php /** @noinspection PhpIncludeInspection */
	include $link['root'].'includes/header.template.php'; ?>

	<!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
	<?php /** @noinspection PhpIncludeInspection */
	include $link['root'].'includes/font.helper.php'; ?>
</head>
<body>
<!--IMPORTANT-->
<!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
<?php
include($mainmenu);
?>
<!-- BODY CONTENT -->
<div class="top_infobar addon_dashboard_color ">
	<div class="infobar_wrapper">
		<div class="infobar_inner_wrapper">
			<h2>
				<?php echo $lang['dashboard_infobar_title']; ?>
			</h2>
			<p><?php echo $lang['dashboard_infobar_desc']; ?></p>
		</div>
	</div>
	<!-- Addon page navigation top menu -->
	<div class="secondery_nav" id="secondery_nav">
		<div id="nav" class="secondery_nav_wrap">
			<ul class="left">
				<li class="expand">
					<a href="javascript:void(0)" onclick="expand_second_menu()"><i class="fa fa-bars"></i></a>
				</li>
				<li>
					<a href="#dashboard_overview" data-href="dashboard_overview"><i class="fa fa-bar-chart"></i>&nbsp;&nbsp;<?php echo $lang['dashboard_menu_1']; ?></a>
				</li>
				<li>
					<a href="#dashboard_all" data-href="dashboard_all"><i class="fa fa-bars"></i>&nbsp;&nbsp; <?php echo $lang['dashboard_menu_2']; ?>
					</a>
				</li>
				<li>
					<a href="#dashboard_submit" data-href="dashboard_submit"><i class="fa fa-plus"></i>&nbsp;&nbsp; <?php echo $lang['dashboard_menu_3']; ?>
					</a>
				</li>

				<li>
					<div id="loading_icon" class="spinner fadeIn animated" style="display:none;">
						<div class="double-bounce1"></div>
						<div class="double-bounce2"></div>
					</div>
				</li>
			</ul>
			<ul class="right">
				<?php if($mb['user']['can_mod']): ?>
					<li>
						<a href="#mod_view" data-href="mod_view"><?php echo $lang['dashboard_menu_4']; ?></a>
					</li>
					<?php if($mb['user']['is_admin']): ?>
						<li>
							<a href="#mbrelease_view" data-href="mbrelease_view">
								<i class="fa fa-download"></i>&nbsp;&nbsp; <?php echo $lang['musicbee_release']; ?>
							</a>
						</li>
						<li>
							<a href="#admin_setting" data-href="admin_setting" title="<?php echo $mb['user']['rank_name']; ?>">
								<i class="fa fa-shield"></i>&nbsp;&nbsp; <?php echo $mb['user']['rank_name']; ?>
							</a>
							<ul class="nav_dropdown_sub">
								<li>
									<a href="#admin_setting" data-href="admin_setting">
										<?php echo $lang['dashboard_menu_6']; ?>
									</a>
								</li>
							</ul>
						</li>
						<?php
					endif;
				endif; ?>
			</ul>
		</div>
	</div>
</div>
<div id="main">
	<div id="main_panel">
		<div class="content_wrapper_admin" id="ajax_area">

		</div>
	</div>
</div>
<!--IMPORTANT-->
<!-- INCLUDE THE FOOTER AT THE END -->
<?php
include($footer);
?>

<script src="<?php echo GetScriptDir(); ?>jquery.sticky.min.js"></script>
<script src="<?php echo GetScriptDir(); ?>markdown-it-classy.js"></script>
<script src="<?php echo GetScriptDir(); ?>markdown-it-video.js"></script>
<script src="<?php echo GetScriptDir(); ?>markdown-it.min.js"></script>
<script src="<?php echo GetScriptDir(); ?>highlight/highlight.pack.js"></script>
<script src="<?php echo GetScriptDir(); ?>markdownEditor.js"></script>
<script src="<?php echo GetScriptDir(); ?>modalBox.js"></script>

<script>
	var defaultpage = "dashboard_overview";
</script>

<?php
include_once $link['root'].'includes/ajax.navigation.script.php';
?>

</body>
</html>
