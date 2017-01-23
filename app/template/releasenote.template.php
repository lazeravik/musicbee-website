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

<?php
//Get release note data
$releasenoteData = getVersionInfo(0, "byAllReleases");
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $lang['releasenote_title']; ?></title>
	<meta name="description" content="<?php echo $lang['releasenote_desc']; ?>">

	<!--include common meta tags and stylesheets -->
	<?php include $link['root'].'includes/header.template.php'; ?>
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
	<div class="top_infobar help_bg_color" id="top_jump">
		<div class="infobar_wrapper">
			<div class="infobar_inner_wrapper">
				<h2><?php echo $lang['release-note']; ?></h2>
			</div>
		</div>
		<?php secondery_nav_generator('release-note'); ?>
	</div>

	<div id="main_panel">
		<div class="main_content_wrapper col_1">
			<div class="sub_content_wrapper">

				<?php
				if(count($releasenoteData) > 0) {
					foreach($releasenoteData as $key => $value):
						?>

						<div class="box_content" id="<?php echo str_replace(".", "-", $value['version']); ?>">
						<span class="show_info custom info_silverwhite release_note_info">
							<ul class="flat_info_bar">
								<li>
									<?php echo $value['appname']; ?>
								</li>
								<li>
									<?php echo sprintf($lang['version_number'], $value['version']); ?>
								</li>
								<li>
									<?php echo sprintf($lang['released_on_date'], $value['release_date']); ?>
								</li>
								<li>
									<?php echo sprintf($lang['for_os'], $value['supported_os']); ?>
								</li>
								<?php if($value['version'] == $mb['musicbee_download']['stable']['version'] || !empty($value['major'])): ?>
									<li>
										<?php echo ($value['version'] == $mb['musicbee_download']['stable']['version']) ? '<p class="small_info active">'.$lang['current_release'].'</p>' : ''; ?>
										<?php echo ($value['major'] == 1) ? '<p class="small_info major">'.$lang['major_release'].'</p>' : ''; ?>
									</li>
								<?php endif; ?>
							</ul>
						</span>
							<div class="info_table_wrap markdownView box">
								<?php echo ($value['release_note_html'] != null) ? $value['release_note_html'] : '<div class="no_release_note" >'.$lang['no_release_note'].'</div>'; ?>
							</div>
						</div>
					<?php endforeach;
				} else {
					echo '<div class="box_content" ><div class="markdownView box"><h2>'.$lang['addon_32'].'</h2></div></div>';
				}

				?>
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
<script src="<?php echo GetScriptDir(); ?>highlight/highlight.pack.js"></script>
<script src="<?php echo GetScriptDir(); ?>jquery.sticky.min.js"></script>
<script>
	$(document).ready(function () {
		document.getElementById('release_note_jump').onchange = function () {
			var $id = '#' + this.getElementsByTagName('option')[this.selectedIndex].value;
			$('html,body').animate({scrollTop: $($id).offset().top - '48'});
		}
	});

	$(document).ready(function () {
		/* Code blocks that do not have code type mentioned we will simply use "CODE" to display*/
		$("pre > code").not('[lang-rel]')
				.each(function () {
					$(this).attr('lang-rel', 'code');
				});


			//add target="_blank" to each link element
			$(".markdownView  a").not('[target]')
				.each(function () {
					$(this).attr('target', '_blank');
				});



		hljs.initHighlightingOnLoad();
	});
</script>
</body>
</html>