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
	<title><?php echo $lang['download_title']; ?></title>
	<meta name="description" content="<?php echo $lang['download_desc']; ?>">
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
<div class="main_panel">
	<div class=" mb_landing  content mb_download_installer_bg">
		<div class="sub_content">
			<h2><?php echo $lang['download_header']; ?></h2>
			<h4><?php echo $lang['download_sub_header']; ?></h4>
			<div class="installer_panel_wrapper">
				<div class="installer_layer_wrapper">
					<h2><?php echo $lang['mb']; ?> <?php echo $mb['musicbee_download']['stable']['version'];?></h2>
					<p><?php echo sprintf($lang['released_on_date'], $mb['musicbee_download']['stable']['release_date']);?></p>
					<p><?php echo sprintf($lang['for_os'], $mb['musicbee_download']['stable']['supported_os']);?></p>
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
						<a href="<?php echo $mb['musicbee_download']['stable']['download']['portable']['link1']; ?>" target="_blank" class="btn btn_blacknwhite btn_download">
							<?php echo $lang['download_get_portable']; ?>
						</a>
					</div>

					<?php if($mb['musicbee_download']['patch'] != null): ?>
						<div class="space micro"></div>
						<div class="installer_layer_wrapper mb_portable_download_bg">
							<h3><?php echo $lang['latest_patch']; ?></h3>
							<p><?php echo sprintf($lang['version_number'], $mb['musicbee_download']['patch']['version']); ?></p>
							<p><?php echo sprintf($lang['released_on_date'], $mb['musicbee_download']['patch']['release_date']); ?></p>
							<hr class="line">
							<p><?php echo $lang['latest_patch_desc']; ?></p>
							<a href="<?php echo $mb['musicbee_download']['patch']['DownloadLink']; ?>" target="_blank" class="btn btn_blacknwhite btn_download">
								<?php echo $lang['download']; ?>
							</a>
						</div>
					<?php else: ?>
						<div class="space micro"></div>
					<?php endif; ?>

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
			<div class="download_img_link_wrapper">
				<div class="download_img_links">
					<ul>
						<li>
							<a href="<?php echo $link['help']; ?>">
								<i class="fa fa-graduation-cap learn"></i>
								<h1><?php echo $lang['learn_features']; ?></h1>
								<p><?php echo $lang['leanr_feature_desc']; ?></p>
							</a>

						</li>
						<li>
							<a href="<?php echo $link['addon']['home']; ?>">
								<i class="fa fa-puzzle-piece plugin"></i>
								<h1><?php echo $lang['get_skin']; ?></h1>
								<p><?php echo $lang['get_skin_desc']; ?></p>
							</a>

						</li>
						<li>
							<a href="<?php echo $link['forum']; ?>">
								<i class="fa fa-comments-o community"></i>
								<h1><?php echo $lang['meet_community']; ?></h1>
								<p><?php echo $lang['meet_community_desc']; ?></p>
							</a>

						</li>
						<?php if(!empty($setting['paypalDonationLink'])): ?>
						<li>
							<a href="<?php echo $setting['paypalDonationLink']; ?>" target="_blank">
								<i class="fa fa-heartbeat love"></i>
								<h1><?php echo $lang['love_musicbee']; ?></h1>
								<p><?php echo $lang['love_musicbee_desc']; ?></p>
							</a>
							<?php endif; ?>

						</li>
					</ul>
				</div>
			</div>
			<div id="clear"></div>
		</div>
	</div>

	<?php if($mb['musicbee_download']['beta']['download']['available'] == 1) : ?>
		<div class="content mb_beta_download_bg" id="beta">
			<div class="sub_content">
				<h2><?php echo $lang['download_h_beta']; ?></h2>
				<h4><b><?php echo $lang['download_h_beta_desc']; ?></b></h4>
				<p><?php echo sprintf($lang['version_number'], $mb['musicbee_download']['beta']['version']); ?></p>
				<p><?php echo sprintf($lang['released_on_date'], $mb['musicbee_download']['beta']['release_date']); ?></p>
				<p><?php echo sprintf($lang['for_os'], $mb['musicbee_download']['beta']['supported_os']); ?></p>
				<br/>
				<?php if($mb['musicbee_download']['beta']['message'] != null)
					echo '<p class="show_info">'.$mb['musicbee_download']['beta']['message'].'</p>';?>
				<a href="<?php echo $mb['musicbee_download']['beta']['download']['link1']; ?>" target="_blank" class="btn btn_blacknwhite btn_download">
					<?php echo $lang['download_get_beta']; ?>
				</a>
			</div>
		</div>
	<?php else: ?>
		<div class="space tiny"></div>
	<?php endif; ?>
</div>

<!--IMPORTANT-->
<!-- INCLUDE THE FOOTER AT THE END -->
<?php
include($footer);
?>
</body>
</html>
