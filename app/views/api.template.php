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

$data = Format::parsePage($link['url'].'app/pages/api/webapi.md');

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
	<title><?php echo $lang['api_title']; ?></title>
	<meta name="description" content="<?php echo $lang['api_desc']; ?>">

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
<div class="top_infobar help_bg_color" id="top_jump">
	<div class="infobar_wrapper">
		<div class="infobar_inner_wrapper">
			<h2><?php echo $lang['api_title']; ?></h2>
		</div>
	</div>
	<?php secondery_nav_generator('api'); ?>
</div>

<div id="main_panel">
	<div class="main_content_wrapper col_2_1">
		<div class="sub_content_wrapper">
		<?php if($mb['help']['api_html']['data'] != null): ?>
			<div class="box_content" id="mbapi">
				<div class="markdownView box faq">
					<?php echo $mb['help']['api_html']['data']; ?>
				</div>
			</div>
		<?php endif; ?>

			<div class="box_content" id="webapi">
				<div class="show_info custom info_silver">
					<h3><?php echo $data['title']; ?></h3>
					<p class="description"><?php echo $data['description']; ?></p>
				</div>
				<div class="markdownView box faq">
					<?php echo $data['html_safe']; ?>
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
<script src="<?php echo GetScriptDir(); ?>highlight/highlight.pack.js"></script>

<script>
	$(document).ready(function () {
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
