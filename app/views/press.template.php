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
	<title><?php echo $lang['press_title']; ?></title>
	<meta name="description" content="<?php echo $lang['press_desc']; ?>">

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
/** @noinspection PhpIncludeInspection */
include($mainmenu);
?>

<!-- BODY CONTENT -->
<div id="main">
	<div class="top_infobar help_bg_color" id="top_jump">
		<div class="infobar_wrapper">
			<div class="infobar_inner_wrapper">
				<h2><?php echo $lang['press_1']; ?></h2>
			</div>
		</div>
		<?php secondery_nav_generator('press'); ?>
	</div>


	<div id="main_panel">
		<div class="main_content_wrapper col_2_1">
			<div class="sub_content_wrapper">
				<div class="box_content">
					<div class="markdownView box">
						<?php 
						if($mb['help']['press_html']['data'] != null){
							echo $mb['help']['press_html']['data']; 
						} else {
							echo '<h2>'.$lang['addon_32'].'</h2>';
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="space medium"></div>
<!--IMPORTANT-->
<!-- INCLUDE THE FOOTER AT THE END -->
<?php
/** @noinspection PhpIncludeInspection */
include($footer);
?>
<script src="<?php echo GetScriptDir(); ?>jquery.sticky.min.js"></script>
<script>
	$(document).ready(function () {
		//add target="_blank" to each link element
		$(".markdownView  a").not('[target]')
			.each(function () {
				$(this).attr('target', '_blank');
			});
	});
</script>
</body>
</html>