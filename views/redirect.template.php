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
	<title>Redirect</title>

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
							<h3>Please Wait! </h3>
							<p class="description">You will be redirected to the download page soon.</p>
							<p class="description">You can <a href="<?php echo $url; ?>">Click here</a> to continue</p>
						</span>
      					
					<?php else: ?>
						<span class="show_info danger custom">
							<h3>URL Parameter is incorrect</h3>
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
<script src="<?php echo $link['url']; ?>scripts/jquery-2.1.4.min.js"></script>
<script src="<?php echo $link['url']; ?>scripts/menu.navigation.js"></script>
</body>
</html>