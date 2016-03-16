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

require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';
?>

<!-- strating the document -->
<!DOCTYPE html>
<html>
<head>
	<title>MusicBee - Downloads</title>
	<!--include common meta tags and stylesheets -->
	<?php include ('./includes/meta&styles.php'); ?>
	<!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
	<?php include ('./includes/font.helper.php'); ?>
</head>
<body>
	<div id="indexBackground">
		<div id="wrapper">
			
			<!--IMPORTANT-->
			<!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
			<?php 
			include($mainmenu); 
			?>
			<!-- BODY CONTENT -->
			<div class="main">
				<div class="main_panel">
					<div class="content mb_download_installer_bg">
							<div class="sub_content">
								<h2>Download MusicBee</h2>
								<h4>Start enjoying your music experience with MusicBee!</h4>
								<div class="installer_panel_wrapper">
									<div class="installer_layer_wrapper">
										<h2>MusicBee <?php echo $release['stable']['version'];?></h2>
										<p>Released on <?php echo $release['stable']['date'];?></p>
										<p>For <?php echo $release['stable']['os'];?></p>
										<p>View the <a href="<?php echo $link['release-note']; ?>">release notes</a></p>
									</div>
							<?php if ($release['stable']['available'] == 1) : ?>
									<div class="installer_layer_wrapper installer_non_portable">
										<h3>Installer Edition</h3>
										<p>
											This edition will install MusicBee on your Windows system drive
										</p>
										<a href="<?php echo $release['stable']['link1']; ?>" target="_blank" class="btn btn_blacknwhite btn_download">
											<i class="fa fa-download"></i> Get MusicBee Installer
										</a><br/>
										<a href="<?php echo $release['stable']['link2']; ?>" target="_blank" class="btn btn_blacknwhite btn_download">
											Mirror 1
										</a> 
										<a href="<?php echo $release['stable']['link3']; ?>" target="_blank" class="btn btn_blacknwhite btn_download">
											Mirror 2
										</a>
									</div>
									
									<div class="installer_layer_wrapper mb_portable_download_bg">
										<h3>Portable Edition</h3>
										<p>This edition can be installed in other locations, such as another partition or USB drive</p>
										<a href="<?php echo $release['stable']['link4']; ?>" target="_blank" class="btn btn_blacknwhite">
											<i class="fa fa-download"></i> Get MusicBee Portable
										</a>

									</div>
								<?php else: ?>
									<div class="installer_layer_wrapper installer_non_portable">
										<h3>Installer Downloads are disabled!</h3>
									</div>
									<div class="installer_layer_wrapper mb_portable_download_bg">
										<h3 class="installer_header_h3">Portable Downloads are disabled!</h3>
										<br/>
										<p class="show_info warning">
											Sorry for the inconvenience. This is probably temporary. Please check the 
											forum or come back later.
										</p>
									</div>
								<?php endif; ?>
								<div id="clear"></div>
								</div>
								<div id="clear"></div>
							</div>
					</div>
					
					<div class="content mb_beta_download_bg">
						<div class="sub_content">
							<h2>MusicBee Beta</h2>
							<h4><b>The beta version is under active development and is not the current stable version</b></h4>
							<p>It is intended for anyone interested in trying the latest features or bug fixes.
							   You should regularly check the forum for updates.</p>
							   <?php if($release['beta']['available'] == 1) : ?>
								<p>Version: <?php echo $release['beta']['version']; ?></p>
								<p>Released on <?php echo $release['beta']['date']; ?></p>
								<p>For <?php echo $release['beta']['os']; ?></p>
								<br/>
								<?php if($release['beta']['message'] != null)
											echo '<p class="show_info">'.$release['beta']['message'].'</p>';?>
								<a href="<?php echo $release['beta']['link1']; ?>" target="_blank" class="btn btn_blacknwhite btn_download">
									<i class="fa fa-warning"></i> Get MusicBee Beta
								</a>
							<?php else: ?>
								<p class="show_info warning">Download is Disabled for MusicBee Beta, checkout our forum for more info</p>
							<?php endif; ?>
								
							</div>
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