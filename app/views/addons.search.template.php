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

$type_blob = (array_key_exists($data['type'], $mb['main_menu']['add-ons']['sub_menu']))? $mb['main_menu']['add-ons']['sub_menu'][$data['type']]['title'] : $data['type'];

?>
<!DOCTYPE html>
<html>
<head profile="http://a9.com/-/spec/opensearch/1.1/">
	<title><?php echo ($data['type'] == "All") ? "Add-ons" : $type_blob; ?> for MusicBee</title>
	<meta name="description" content="<?php echo $meta_description; ?>">
	<!-- keyword meta tag is obsolete, google does not use it, but some
	search engine still use it, so for legacy support it is included -->
	<meta name="keywords" content="addons, plugins, skins, theater mode, musicbee, music, player, unltimate, best, customizable, skin, free, plugin, download">

	<!--include common meta tags and stylesheets -->
	<?php include $link['root'].'includes/header.template.php'; ?>

	<!-- Opensearch-->
	<link rel="search" type="application/opensearchdescription+xml" href="<?php echo $link['url']; ?>opensearch.xml" title="<?php echo $lang['addon_opensearch_title']; ?>" />


	<?php
	/**
	 * Search engine and SEO optimization starts here.
	 *
	 * Provide link relation for previous page and next page for search engines to better Index the pages
	 */

	if($generated_url_prev!=null): ?>
		<link rel="prev" href="<?php echo $generated_url_prev; ?>"/>
	<?php endif;
	if($generated_url_next!=null): ?>
		<link rel="next" href="<?php echo $generated_url_next; ?>"/>
	<?php endif; ?>


	<!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
	<?php include $link['root'].'includes/font.helper.php'; ?>
	<link rel="stylesheet" href="<?php echo GetStyleDir(); ?>animate.css">
</head>
<body>

<!--IMPORTANT-->
<!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
<?php
include($mainmenu);
?>
<!-- BODY CONTENT -->
<div class="top_infobar addon_browse_bg_color" id="top_jump">
	<div class="infobar_wrapper">
		<div class="infobar_inner_wrapper">
			<h2><?php echo $lang['addon_46']; ?></h2>

			<div class="right_btn">
				<a href="<?php echo $link['forum']; ?>"><?php echo $lang['addon_48']; ?></a>
				<a href="<?php echo $link['addon']['dashboard']; ?>#dashboard_submit"><?php echo $lang['addon_50']; ?></a>
				<a href="<?php echo $link['addon']['home']; ?>s/?type=all"><?php echo $lang['addon_51']; ?></a>
			</div>
		</div>
	</div>
	<div class="secondery_nav addon_secondery_nav secondery_nav_color" id="secondery_nav">
		<div class="secondery_nav_wrap">
			<?php echo addon_secondery_nav_generator($addon_type); ?>
		</div>
	</div>
</div>

<?php if($data['is_overview']): ?>
	<div class="addon_similar addon_cat_header">
		<div class="addon_similar_wrap addon_result_column_wrap col_2_1">
			<div class="addon_result_column">
				<h2><?php echo $lang['addon_52']; ?></h2>
				<?php
				echo addon_result_view_generator($data['addon_data_like']['result']);
				?>
			</div>
			<div class="addon_result_column alternate_bg">
				<h2><?php echo $lang['addon_57']; ?></h2>
				<?php echo top_member_result_generator($data['top_members']); ?>
			</div>
		</div>
	</div>
	<div class="addon_similar addon_cat_header alternate_bg">
		<hr class="line"/>
		<div class="addon_similar_wrap header_links">
			<?php echo $lang['addon_53']; ?>
			<a href="<?php echo $link['addon']['home']; ?>s/?q=Service">Services</a>
			<a href="<?php echo $link['addon']['home']; ?>s/?q=Device">Devices</a>
			<a href="<?php echo $link['addon']['home']; ?>s/?q=Audio">Audio</a>

		</div>
		<hr class="line"/>
	</div>
	<div class="addon_similar addon_cat_header">
		<div class="addon_similar_wrap">
			<h2><?php echo $lang['addon_54']; ?></h2>
			<?php
			echo addon_result_view_generator($data['addon_data_new']['result']);
			?>
		</div>
	</div>

	<div class="addon_similar addon_cat_header alternate_bg">
		<div class="addon_similar_wrap">
			<h2><?php echo $lang['addon_55']; ?></h2>
			<?php
			echo addon_result_view_generator($data['addon_data_updated']['result']);
			?>
		</div>
	</div>
	<div class="addon_similar addon_cat_header alternate_bg">
		<div class="more_addon">
			<a class="btn btn_wireframe btn_wireframe_blue" href="<?php echo $generated_url; ?>">
				<h3><?php echo $lang['show_all']; ?></h3>
			</a>
		</div>
	</div>
<?php else: ?>
	<div class="addon_similar addon_cat_header">
		<div class="addon_similar_wrap">
			<?php

			if(!empty($url_params['q'])) {
				echo "<h2>".$lang['addon_31']."<i class=\"search_term\">".htmlspecialchars($url_params['q'], ENT_QUOTES, "UTF-8")."</i>"."<i class=\"search_term\">".$type_blob."</i></h2>";
			} else {
				echo "<h2>".$type_blob."</h2>";
				if(isset($mb['main_menu']['add-ons']['sub_menu'][$addon_type]['desc'])) {
					echo "<h4>".$mb['main_menu']['add-ons']['sub_menu'][$addon_type]['desc']."</h4>";
				}
			}
			?>

			<?php
			echo addon_result_view_generator($data['addon_data']['result']);
			echo addon_result_pagination_generator($page_total, $current_page, $generated_url);
			?>
		</div>
	</div>
<?php endif; ?>

<!--IMPORTANT-->
<!-- INCLUDE THE FOOTER AT THE END -->
<?php
include($footer);
?>

<script src="<?php echo GetScriptDir(); ?>jquery.sticky.min.js"></script>
<script type="text/javascript">

</script>
</body>
</html>