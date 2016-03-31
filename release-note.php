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

include $_SERVER['DOCUMENT_ROOT'].'/functions.php';

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $lang['releasenote_title']; ?></title>
	<meta name="description" content="<?php echo $lang['releasenote_desc']; ?>">

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
	<div id="main">
		<div class="top_infobar mb_release_note_color" id="top_jump">
			<div class="infobar_wrapper">
				<div class="infobar_inner_wrapper">
					<h2><?php echo $lang['releasenote_infobar_title']; ?></h2>
					<p><?php echo $lang['releasenote_infobar_desc']; ?></p>
				</div>

			</div>
			<div id="secondery_nav-sticky-wrapper" class="sticky-wrapper" style="height: 48px;"><div class="secondery_nav" id="secondery_nav">
				<div id="nav" class="secondery_nav_wrap" data-scroll-header>
					<ul class="left">

					</ul>
					<ul class="right">
						<li>
							<select name="release_note_jump" id="release_note_jump" onfocus="" >
								<option value="top_jump"><?php echo $lang['releasenote_infobar_1']; ?></option>
								<?php 
								foreach (getVersionInfo(0,"byAllReleases") as $key => $value) {
									echo '<option>'.str_replace(".", "-", $value['version']) .'</option>';
								}
								?>
							</select>
						</li>
					</ul>
				</div>
			</div></div>
		</div>
		<div id="main_panel">
		<div class="main_content_wrapper col_1">
				<div class="sub_content_wrapper">
					<?php 
					foreach (getVersionInfo(0,"byAllReleases") as $key => $value): ?>
					<div class="box_content" id="<?php echo str_replace(".", "-", $value['version']); ?>">
						<span class="show_info <?php echo ($value['version']==$release['stable']['version'])?'info_green':'info_darkgrey'; ?> release_note_info">
							<ul class="flat_info_bar">
								<li>
									<?php echo $value['appname']; ?>
								</li>
								<li>
									Version <?php echo $value['version'] ?>
								</li>
								<li>
									Released on <?php echo $value['release_date']; ?>
								</li>
								<li>
									For <?php echo $value['supported_os']; ?>
								</li>
								<li>
									<?php echo ($value['version']==$release['stable']['version'])?'<p class="small_info active">Current Release</p>':''; ?>
									<?php echo ($value['major']==1)?'<p class="small_info major">major Release</p>':''; ?>
								</li>
							</ul>	
						</span>
						<div class="info_table_wrap markdownView" >
							<?php echo ($value['release_note_html']!=null)?$value['release_note_html']:'<div class="no_release_note" data-no-release-text="No Release Notes/Change Logs are provided for this version.&#xa;This is most likely a minor version and does not contain major changes"></div>'; ?>
						</div>
					</div>
				<?php endforeach; ?>
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
<script src="<?php echo $link['url']; ?>scripts/highlight/highlight.pack.js"></script>
<script src="<?php echo $link['url']; ?>scripts/jquery.sticky.min.js"></script>
<script src="<?php echo $link['url']; ?>scripts/menu.navigation.js"></script>
<script>
	$(document).ready(function(){
		document.getElementById('release_note_jump').onchange = function(){
			$id = '#'+this.getElementsByTagName('option')[this.selectedIndex].value;
			$('html,body').animate({scrollTop: $($id).offset().top-'48'});
		}
	});

	$(document).ready(function(){
		/* Code blocks that do not have code type mentioned we will simply use "CODE" to display*/
		$( "pre > code" ).not('[lang-rel]')
		.each(function() {
			$(this).attr('lang-rel', 'code');
		});

		hljs.initHighlightingOnLoad();
	});
</script>
</body>
</html>