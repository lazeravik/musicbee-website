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
	<title><?php echo $lang['help_title']; ?></title>
	<meta name="description" content="<?php echo $lang['help_desc']; ?>">

	<!--include common meta tags and stylesheets -->
	<?php
	include $link['root'].'includes/header.template.php'; ?>

	<!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
	<?php
	include $link['root'].'includes/font.helper.php'; ?>
</head>
<body>

<!--IMPORTANT-->
<!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
<?php
include($mainmenu);
?>

<!-- BODY CONTENT -->
<div class="top_infobar help_bg_color" id="top_jump">
	<div class="infobar_wrapper">
		<div class="infobar_inner_wrapper">
			<h2><?php echo $lang['help_title']; ?></h2>
		</div>
	</div>
	<?php secondery_nav_generator('helpfaq'); ?>
</div>

<div id="main_panel">
	<div class="main_content_wrapper col_2_1">
		<div class="sub_content_wrapper">

			<div class="box_content">
				<div class="show_info custom">
					<ul class="grid col_2">
						<?php
						if($mb['help']['help_links']['data'] != null){
							$top_help_links = json_decode($mb['help']['help_links']['data'])->top_help_popup;

							if($top_help_links != null):
								foreach ($top_help_links as $help_link): ?>

									<li>
										<a href="<?php echo $help_link->url; ?>" target="<?php echo isset($help_link->target)? $help_link->target : '' ; ?>">
											<div>
												<?php echo $help_link->icon; ?>
												<p><?php echo $help_link->name; ?></p>
											</div>

										</a>
									</li>
						<?php endforeach;
							endif;
						} ?>
					</ul>
				</div>
			</div>

			<div class="box_content" id="faq">
				<div class="show_info custom info_silver">
					<h3><?php echo $lang['help_faq_headline']; ?></h3>
				</div>
				<div id="faq-view" class="markdownView box faq">
					<?php 
					$html = file_get_contents($mb['help']['help_api_link']['data']);
						echo Format::htmlSafeOutput($html); 
					?>
				</div>
			</div>

		</div>

		<div class="sub_content_wrapper">

			<div class="box_content">
				<div class="show_info custom info_silver">
					<h3><?php echo $lang['user_help']; ?></h3>
				</div>
				<ul class="link_list">
					<?php
					if($mb['help']['help_links']['data'] != null) {

						$user_help_links = json_decode($mb['help']['help_links']['data'])->user_help;

						if ($user_help_links != null):
							foreach ($user_help_links as $help_link): ?>

								<li>
									<a href="<?php echo $help_link->url; ?>"
									   target="<?php echo isset($help_link->target) ? $help_link->target : ''; ?>"><?php echo $help_link->name; ?></a>
								</li>

							<?php endforeach;
						endif;
					}?>
				</ul>
			</div>

			<div class="box_content">
				<div class="show_info custom info_silver">
					<h3><?php echo $lang['dev_guides']; ?></h3>
				</div>
				<ul class="link_list">
					<?php
					if($mb['help']['help_links']['data'] != null) {
						$dashboard_help_links = json_decode($mb['help']['help_links']['data'])->dashboard;

						if ($dashboard_help_links != null):
							foreach ($dashboard_help_links as $help_link): ?>

								<li>
									<a href="<?php echo $help_link->url; ?>"
									   target="<?php echo isset($help_link->target) ? $help_link->target : ''; ?>"><?php echo $help_link->name; ?></a>
								</li>

							<?php endforeach;
						endif;
					}?>
				</ul>
			</div>
			<div class="box_content">
				<div class="show_info custom info_silver">
					<h3><?php echo $lang['help_popular_wiki_headline']; ?></h3>
				</div>
					<ul class="link_list">
					<?php
					try
					{
						$content = file_get_contents($setting['wikiaLink']."/api/v1/Articles/Popular?limit=10");

						if($content != null && $setting['wikiaLink'] != null)
						{
							$content = json_decode($content, true);
							foreach ($content['items'] as $wiki_item)
							{
					?>
								<li>
									<a href="<?php echo $setting['wikiaLink'].$wiki_item['url']; ?>"
									   target="_blank"><?php echo $wiki_item['title']; ?></a>
								</li>
					<?php
							}
						}
					} catch (Exception $e){}
					?>
					</ul>
			</div>


			<div class="box_content">
				<div class="show_info custom info_silver">
					<h3><?php echo $lang['help_mostviewed_wiki_headline']; ?></h3>
				</div>
				<ul class="link_list">
					<?php
					try
					{
						$content = file_get_contents($setting['wikiaLink']."/api/v1/Articles/Top?limit=10");

						if($content != null && $setting['wikiaLink'] != null)
						{
							$content = json_decode($content, true);
							foreach ($content['items'] as $wiki_item)
							{
								?>
								<li>
									<a href="<?php echo $setting['wikiaLink'].$wiki_item['url']; ?>"
									   target="_blank"><?php echo $wiki_item['title']; ?></a>
								</li>
								<?php
							}
						}
					} catch (Exception $e){}
					?>
				</ul>
			</div>


			<div class="box_content">
				<div class="show_info custom info_silver">
					<h3><?php echo $lang['help_new_wiki_headline']; ?></h3>
				</div>
				<ul class="link_list">
					<?php
					try
					{
						$content = file_get_contents($setting['wikiaLink']."/api/v1/Articles/New?limit=20&minArticleQuality=10");

						if($content != null && $setting['wikiaLink'] != null)
						{
							$content = json_decode($content, true);
							foreach ($content['items'] as $wiki_item)
							{
								?>
								<li>
									<a href="<?php echo $setting['wikiaLink'].$wiki_item['url']; ?>"
									   target="_blank"><?php echo $wiki_item['title']; ?></a>
								</li>
								<?php
							}
						}
					} catch (Exception $e){}
					?>
				</ul>
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