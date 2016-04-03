<!DOCTYPE html>
<html>
<head>
	<title><?php echo ($data['type'] == "All") ? "Add-ons" : $data['type']; ?> for MusicBee</title>
	<meta name="description" content="<?php echo $meta_description; ?>">
	<!-- keyword meta tag is obsolete, google does not use it, but some
	search engine still use it, so for legacy support it is included -->
	<meta name="keywords" content="addons, plugins, skins, theater mode, musicbee, music, player, unltimate, best, customizable, skin, free, plugin, download">
	<!--include common meta tags and stylesheets -->
	<?php include $link['root'].'includes/meta&styles.php'; ?>
	<!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
	<?php include $link['root'].'includes/font.helper.php'; ?>
	<link rel="stylesheet" href="<?php echo $link['url']; ?>styles/animate.css">

</head>
<body>

<!--IMPORTANT-->
<!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
<?php
include($mainmenu);
?>
<!-- BODY CONTENT -->
<div class="addon_similar addon_cat_header alternate_bg blue">
	<div class="addon_similar_wrap heading_intro header_links">
		<h2><?php echo $lang['addon_46']; ?></h2>
		<p><?php echo $lang['addon_45']; ?></p>
		<a href="<?php echo $link['help']; ?>"><?php echo $lang['addon_49']; ?></a>
		<a href="<?php echo $link['forum']; ?>"><?php echo $lang['addon_48']; ?></a>
		<a href="<?php echo $link['addon']['dashboard']; ?>#dashboard_submit"><?php echo $lang['addon_50']; ?></a>
		<a href="<?php echo $generated_url; ?>"><?php echo $lang['addon_51']; ?></a>
	</div>
	<!-- AddOn page navigation top menu -->
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
			<a href="<?php echo $link['addon']['home']; ?>s/?q=Service&type=plugins&order=latest">Services</a>
			<a href="<?php echo $link['addon']['home']; ?>s/?q=Device&type=plugins&order=latest">Devices</a>
			<a href="<?php echo $link['addon']['home']; ?>s/?q=Audio&type=plugins&order=latest">Audio</a>
			<a href="<?php echo $link['addon']['home']; ?>s/?q=&type=plugins&order=latest">General & Misc</a>

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
				<h3><?php echo $lang['addon_23']; ?></h3>
			</a>
		</div>
	</div>
<?php else: ?>
	<div class="addon_similar addon_cat_header">
		<div class="addon_similar_wrap">
			<?php
			if(!empty($url_params['q'])) {
				echo "<h2>".$lang['addon_31']."<i class=\"search_term\">".htmlspecialchars($url_params['q'], ENT_QUOTES, "UTF-8")."</i>"."<i class=\"search_term\">".$data['type']."</i></h2>";
			} else {
				echo "<h2>".$data['type']."</h2>";
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

<script type="text/javascript" src="<?php echo $link['url']; ?>scripts/jquery-2.1.4.min.js"></script>
<script src="<?php echo $link['url']; ?>scripts/jquery.sticky.min.js"></script>
<script src="<?php echo $link['url']; ?>scripts/menu.navigation.js"></script>
<script type="text/javascript">

</script>
</body>
</html>