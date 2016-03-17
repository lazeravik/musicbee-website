<!DOCTYPE html>
<html>
<head>
	<title><?php echo ucfirst($data['type']); ?> for MusicBee | Add-ons</title>
	<meta name="description" content="<?php echo $meta_description; ?>">
		<!-- keyword meta tag is obsolete, google does not use it, but some
		search engine still use it, so for legacy support it is included -->
		<meta name="keywords" content="musicbee, music, player, unltimate, best, customizable, skin, free, plugin, download">

		<!--include common meta tags and stylesheets -->
		<?php include $siteRoot . 'includes/meta&styles.php'; ?>
		<!--Use OWL carousel for image screenshot slider -->
		<link rel="stylesheet" href="<?php echo $siteUrl; ?>styles/magnific-popup.css">
		<!-- Used for slider animation -->
		<link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>styles/MusicBeeAddons.css">
		<!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
		<?php include $siteRoot . 'includes/font.helper.php'; ?>
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
				<div id="main">
					<div id="main_panel">
						<!-- addon page INFO SECTION STARTS -->
						<div id="bg_image" class="general_info">
							<!-- AddOn page navigation top menu -->
							<div class="secondery_nav addon_secondery_nav secondery_nav_color" id="secondery_nav">
								<div class="secondery_nav_wrap">
									<?php addon_secondery_nav_generator($addon_type); ?>
								</div>
							</div>
							<div class="mb_addon_bg">
								<div id="overlay_bg" class="search_box_outer_wrap">
									<div class="sub_content">
										<div class="search_box">
											<div class="search_box_wrap">
												<form method="GET" action="<?php echo $link['addon']['home']; ?>s/">
													<p><label for="order">Search: </label></p>
														<input type="text" spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off" class="search big_search" id="big_search" name="q" placeholder="Search <?php echo $data['type']; ?>" value="<?php echo htmlspecialchars($url_params['q'], ENT_QUOTES, "UTF-8"); ?>"/>
														<input type="hidden" name="search" value="true" />
													<div class="search_filter_wrap">
														<div class="search_filter_cat">
															<div class="search_cat_input">
																<p><label for="type">Category: </label></p>
																<select name="type" id="type">
																	<?php
																	echo "<option value=\"all\" ";
																	if ($data['type'] == "All")
																		echo "selected";
																	echo ">All</option>";
																	foreach ($main_menu['add-ons']['sub_menu'] as $key => $menu_addon) {
																		echo "<option value=\"" . Format::Slug($menu_addon['title']) . "\"";
																		if ($addon_type == Format::Slug($menu_addon['title']))
																			echo "selected";
																		echo ">" . $menu_addon['title'] . "</option>";
																	}
																	?>
																</select>
															</div>
														</div>
														<div class="search_filter_cat">
															<div class="search_cat_input search_cat_input_last">
																<p><label for="order">Order By: </label></p>
																<select name="order" id="order">
																	<option value="latest">Latest</option>
																	<option value="oldest">Oldest</option>
																	<!-- <option value="likes">Likes</option> -->
																</select>
															</div>
														</div>
													</div>
												</form>
											</div>
										</div>

									</div>
								</div>
							</div>

							<div class="addon_similar addon_cat_header">
								<hr class="line" />
								<div class="addon_similar_wrap">
									<?php 
									if (!empty($url_params['q'])) {
										echo "<h2>".$lang['269']."<i class=\"search_term\">".htmlspecialchars($url_params['q'], ENT_QUOTES, "UTF-8")."</i>"."<i class=\"search_term\">".$data['type']."</i></h2>";
									} else {
										echo "<h2>".$data['type']."</h2>";
										if (isset($main_menu['add-ons']['sub_menu'][$addon_type]['desc'])) {
											echo "<h4>" . $main_menu['add-ons']['sub_menu'][$addon_type]['desc'] . "</h4>";
										}
									}
									?>

								</div>       
							</div>
							<div class="addon_similar addon_cat_header">
								<div class="addon_similar_wrap">
									<?php 
										echo addon_result_view_generator($data['addon'], $addon);
										echo addon_result_pagination_generator($page_total, $generated_url);
									?>
								</div>
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

		<script type="text/javascript" src="<?php echo $siteUrl; ?>scripts/jquery-2.1.4.min.js"></script>
		<script src="<?php echo $siteUrl; ?>scripts/jquery.sticky.min.js"></script>
		<script type="text/javascript">
			$('#type').on('change', function (event) {
				$('#big_search').attr('placeholder', 'Search ' + $("#type option:selected").text());
			});

			$("#secondery_nav").sticky({topSpacing: 0});
		</script>
	</body>
	</html>