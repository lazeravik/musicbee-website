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
	<title><?php echo $lang['redirect_title']; ?></title>

	<!--include common meta tags and stylesheets -->
	<?php include $link['root'] . '/includes/meta&styles.php'; ?>

	<!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
	<?php include $link['root'] . '/includes/font.helper.php'; ?>
	<meta http-equiv="refresh" content="1;url=<?php echo $url; ?>" />
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
		<div class="main_content_wrapper col_1">
				<div class="sub_content_wrapper">
					<div class="box_content" >
					<?php if (isset($url)) : ?>
						<span class="show_info info_darkgrey custom">
							<h3><?php echo $lang['please_wait']; ?></h3>
							<p class="description"><?php echo $lang['redirect_notice']; ?></p>
							<p class="description"><?php echo sprintf($lang['click_here_redirect'], $url); ?></p>
						</span>
      					
					<?php else: ?>
						<span class="show_info danger custom">
							<h3><?php echo $lang['incorrect_url_param_err']; ?></h3>
						</span>
					<?php endif; ?>
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

</body>
</html>