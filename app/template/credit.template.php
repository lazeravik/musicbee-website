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
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/functions.php';
include_once $link['root'].'includes/parsedown/Parsedown.php';
include_once $link['root'].'includes/parsedown/ParsedownExtra.php';

$data = Format::parsePage($link['url'].'app/pages/credit/credit.md');

//load parsedown for markdown to html converter
$ParsedownExtra = new ParsedownExtra();
$ParsedownExtra->setBreaksEnabled(true);
$data['html_raw'] = $ParsedownExtra->text($data['markdown']);

//load and use html purifier for unsafe html.
$data['html_safe'] = Format::htmlSafeOutput($data['html_raw']); //purify the readme note html

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $data['title']; ?></title>
	<meta name="description" content="<?php echo $data['description']; ?>">

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
				<h2><?php echo  $data['title']; ?></h2>
			</div>
		</div>
		<?php secondery_nav_generator(); ?>
	</div>


	<div id="main_panel">
		<div class="main_content_wrapper col_2_1">
			<div class="sub_content_wrapper">
				<div class="box_content">
					<div class="markdownView box" id="content">
						<?php echo $data['html_safe']; ?>
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
		$("#content a").not('[target]')
			.each(function () {
				$(this).attr('target', '_blank');
			});
	});
</script>
</body>
</html>
