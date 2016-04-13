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
	<title><?php echo $lang['download_title']; ?></title>
	<meta name="description" content="<?php echo $lang['download_desc']; ?>">
	<!--include common meta tags and stylesheets -->
	<?php include ('./includes/meta&styles.php'); ?>
	<!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
	<?php include ('./includes/font.helper.php'); ?>
</head>
<body>
<!--IMPORTANT-->
<!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
<?php
include($mainmenu);
?>
<!-- BODY CONTENT -->
<div class="main">
	<div class="main_panel">
		<div class=" mb_landing  content mb_download_installer_bg">
			<div class="sub_content">
				<h2><?php echo $lang['download_header']; ?></h2>
				<h4><?php echo $lang['download_sub_header']; ?></h4>
				<div class="installer_panel_wrapper">
					<div class="installer_layer_wrapper">
						<h2><?php echo $lang['mb']; ?> <?php echo $mb['musicbee_download']['stable']['version'];?></h2>
						<p><?php echo $lang['releasenote_2']; ?> <?php echo $mb['musicbee_download']['stable']['release_date'];?></p>
						<p><?php echo $lang['releasenote_3']; ?> <?php echo $mb['musicbee_download']['stable']['supported_os'];?></p>
						<p><a href="<?php echo $link['release-note']; ?>"><?php echo $lang['mbr_lbl_8']; ?></a></p>
					</div>
					<?php if ($mb['musicbee_download']['stable']['download']['available'] == 1) : ?>
						<div class="installer_layer_wrapper installer_non_portable">
							<h3><?php echo $lang['download_h_installer']; ?></h3>
							<p>
								<?php echo $lang['download_h_installer_desc']; ?>
							</p>
							<a href="<?php echo $mb['musicbee_download']['stable']['download']['installer']['link1']; ?>" target="_blank" class="btn btn_blacknwhite btn_download">
								<?php echo $lang['download_get_installer']; ?>
							</a>
							<br/>
							<a href="<?php echo $mb['musicbee_download']['stable']['download']['installer']['link2']; ?>" target="_blank" class="btn btn_blacknwhite btn_download">
								<?php echo $lang['download_mirror1']; ?>
							</a>
							<a href="<?php echo $mb['musicbee_download']['stable']['download']['installer']['link3']; ?>" target="_blank" class="btn btn_blacknwhite btn_download">
								<?php echo $lang['download_mirror2']; ?>
							</a>
						</div>

						<div class="installer_layer_wrapper mb_portable_download_bg">
							<h3><?php echo $lang['download_h_portable']; ?></h3>
							<p><?php echo $lang['download_h_portable_desc']; ?></p>
							<a href="<?php echo $mb['musicbee_download']['stable']['download']['portable']['link1']; ?>" target="_blank" class="btn btn_blacknwhite">
								<?php echo $lang['download_get_portable']; ?>
							</a>

						</div>
					<?php else: ?>
						<div class="installer_layer_wrapper installer_non_portable">
							<h3><?php echo $lang['download_disabled_h']; ?></h3>
							<p class="show_info">
								<?php echo $lang['download_disabled_desc']; ?>
							</p>
						</div>
					<?php endif; ?>
					<div id="clear"></div>
				</div>
				<div class="hero_img_top">
					<div class="hero_img_wrapper hero_img_topmost_wrap">
						<img src="<?php echo $link['url']; ?>img/mb-hero-interface-min.png">
					</div>
				</div>
				<div id="clear"></div>
			</div>
		</div>

		<div class="content mb_beta_download_bg">
			<div class="sub_content">
				<h2><?php echo $lang['download_h_beta']; ?></h2>
				<h4><b><?php echo $lang['download_h_beta_desc']; ?></b></h4>
				<?php if($mb['musicbee_download']['beta']['download']['available'] == 1) : ?>
					<p><?php echo $lang['mbr_lbl_2']; ?> <?php echo $mb['musicbee_download']['beta']['version']; ?></p>
					<p><?php echo $lang['releasenote_2']; ?> <?php echo $mb['musicbee_download']['beta']['release_date']; ?></p>
					<p><?php echo $lang['releasenote_3']; ?> <?php echo $mb['musicbee_download']['beta']['supported_os']; ?></p>
					<br/>
					<?php if($mb['musicbee_download']['beta']['message'] != null)
						echo '<p class="show_info">'.$mb['musicbee_download']['beta']['message'].'</p>';?>
					<a href="<?php echo $mb['musicbee_download']['beta']['download']['link1']; ?>" target="_blank" class="btn btn_blacknwhite btn_download">
						<?php echo $lang['download_get_beta']; ?>
					</a>
				<?php else: ?>
					<p class="show_info warning"><?php echo $lang['download_beta_disabled_desc']; ?></p>
				<?php endif; ?>

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
