<!DOCTYPE html>
<html>
<head>
	<title><?php echo $data['addon_title']; ?> for MusicBee</title>
	<meta name="description" content="<?php echo $meta_description; ?>">
	<!-- keyword meta tag is obsolete, google does not use it, but some
	search engine still use it, so for legacy support it is included -->
	<meta name="keywords" content="<?php echo $data['tags']; ?>, musicbee, customizable, skin, free, plugin, download">

	<!--include common meta tags and stylesheets -->
	<?php include $siteRoot . 'includes/meta&styles.php'; ?>

	<link rel="stylesheet" href="<?php echo $siteUrl; ?>styles/magnific-popup.css">
	<!-- Used for slider animation -->
	<link rel="stylesheet" href="<?php echo $siteUrl; ?>styles/animate.css">
	<!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
	<?php include $siteRoot . 'includes/font.helper.php'; ?>
</head>
<body>

<!--IMPORTANT-->
<!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
<?php
include ($mainmenu);
?>
<!-- BODY CONTENT -->
<!-- addon page INFO SECTION STARTS -->
<?php
/**
 * If the addon is soft deleted show error only!
 */
if ($data['status'] == "3"): ?>
	<div class="main_content_wrapper col_2_1">
		<div class="sub_content_wrapper">
			<div class="box_content">
			<span class="show_info info_red custom">
				<h3><?php echo $lang['dashboard_err_18']; ?></h3>
			</span>
				<p class="info_text">
					<?php echo $lang['dashboard_msg_9']; ?>
				</p>
			</div>
		</div>
	</div>
<?php else: ?>
	<div id="bg_image" class="general_info">
		<!-- AddOn page navigation top menu -->
		<div class="secondery_nav addon_secondery_nav" id="secondery_nav">
			<div class="secondery_nav_wrap">
				<?php echo addon_secondery_nav_generator ($addon_type); ?>
			</div>
		</div>
		<div id="overlay_bg" class="general_info_color">
			<div class="general_info_wrap">
				<div class="general_info_text">
					<h2 class="title">
						<?php echo $data['addon_title']; ?>
						<?php if ($data['addon_version'] != null): ?>
							<i class="general_info_addon_version">v<?php echo $data['addon_version']; ?></i>
						<?php endif; ?>
						<?php if ($data['is_beta'] == 1): ?>
							<p class="small_info beta"><?php echo $lang['addon_38']; ?></p>
						<?php endif; ?>
					</h2>
					<div class="general_info_addon_meta">
						<p><?php echo $lang['addon_15']; ?>
							<a href="<?php echo addon_author_url_generator ($data['membername']); ?>"><?php echo $data['membername']; ?></a>
						</p>
						<?php if (null != $data['update_date']): ?>
							<p><?php echo $lang['addon_16']; ?><?php echo $data['update_date']; ?></p>
						<?php else: ?>
							<p><?php echo $lang['addon_17']; ?><?php echo $data['publish_date']; ?></p>
						<?php endif; ?>
						<p><?php echo $lang['addon_36']; ?>
							<b>
								<?php echo ($mbVerArray[0] != null) ? implode (", ", $mbVerArray) : $lang['addon_37']; ?>
							</b>
						</p>
					</div>
					<p class="description"><?php echo $data['short_description']; ?></p>
				</div>
				<div class="general_info_icon_wrap">
					<div class="general_info_icon" style="background-image: url(<?php echo $data['thumbnail']; ?>);"></div>
				</div>
				<?php if (!empty(trim ($data['important_note']))): ?>
					<div class="general_info_sidenote">
						<p>
							<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp; <?php echo $data['important_note']; ?>
						</p>
					</div>
				<?php endif; ?>

				<div class="general_info_downloadlink">

					<?php if ($data['status'] == 2): ?>
					<span class="show_info">
						<h3><?php echo $lang['addon_39']; ?></h3>
						<p class="description"><?php echo $lang['addon_40']; ?></p>
					</span>
					<?php elseif ($data['status'] != 1): ?>
						<span class="show_info danger">
							<h3><?php echo $lang['addon_34']; ?></h3>
							<p class="description"><?php echo $lang['addon_35']; ?></p>
						</span>
					<?php endif; ?>


					<?php if ($data['status'] != 2): ?>
					<a href="<?php echo $link['redirect'] . '?type=addon&id=' . $data['ID_ADDON'] . '&r=' . urlencode ($data['download_links']); ?>" class="btn btn_blue" target="_blank"><i class="fa fa-download"></i> <?php echo $lang['addon_18']; ?>
					</a>
					<?php if (!empty(trim ($data['support_forum']))): ?>
						<a href="<?php echo $data['support_forum']; ?>" class="btn">
							<i class="fa fa-support"></i><?php echo $lang['addon_19']; ?>
						</a>
					<?php endif; ?>
					<?php if (!empty(trim ($data['readme_content_html']))): ?>
						<a href="#readme" class="btn btn_grey" onclick="$('html,body').animate({scrollTop: $('#readme').offset().top});">
							<i class="fa fa-info"></i><?php echo $lang['addon_20']; ?>
						</a>
					<?php endif; ?>
					<a id="like_count" href="javascript:void(0)" class="btn btn_yellow like_btn"
					   onclick="rate('<?php echo $data['ID_ADDON']; ?>')"
					   data-like-count="<?php echo Format::number_format_suffix ($addon_like); ?>">
						<?php echo $lang['addon_25']; ?>
					</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<!--INFO SECTION ENDS -->


	<!-- Screenshot STARTS -->
	<div class="addon_similar">
		<div class="addon_similar_wrap screenshot">
			<ul id="screenshot_all">
				<?php
				foreach ($screenshots as $key => $img) {
					if (preg_match ('/^https?\:\/\/i\.imgur\.com\//', $img)) {
						$ext_pos = strrpos ($img, '.'); // find position of the last dot, so where the extension starts
						$thumb = substr ($img, 0, $ext_pos) . 'm' . substr ($img, $ext_pos);
					} else {
						$thumb = $img;
					}
					echo '<a class="screenshot_zoom_click" href="', $img, '">
						<div class="screenshot_wrapper" style="background-image:url(' . $thumb . ')"></div>
					</a>';
				}
				?>
			</ul>
		</div>
	</div>
	<!-- SLIDER ENDS-->

	<?php if (!empty(trim ($data['readme_content_html']))): ?>
		<!-- WORD FROM AUTHOR STARTS -->
		<div class="addon_similar readme_markdown_bg" id="readme">
			<div class="addon_similar_wrap readme_markdown_wrap">
				<h2><?php echo $lang['addon_21']; ?></h2>
				<div id="readme_markdown" class="markdownView">
					<?php echo $data['readme_content_html']; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<!-- WORD FROM AUTHOR ENDS-->


	<!-- MORE FROM AUTHOR STARTS -->
	<div class="addon_similar more_from_author">
		<div class="addon_similar_wrap from_author">
			<h2><?php echo $lang['addon_22'] . $data['membername']; ?></h2>
			<?php
			echo addon_result_view_generator ($from_author);
			?>
			<div class="more_addon">
				<a class="btn btn_wireframe btn_wireframe_blue" href="<?php echo addon_author_url_generator ($data['membername']); ?>">
					<h3><?php echo $lang['addon_23']; ?></h3>
					<p><?php echo $lang['addon_24']; ?><?php echo $data['membername']; ?></p>
				</a>
			</div>

			<p class="license_attr"><?php echo $lang['addon_license_1']; ?>
				<a href="http://creativecommons.org/licenses/by-sa/3.0/" target="_blank">cc by-sa 3.0</a>
			</p>

		</div>
	</div>
	<!-- MORE FROM AUTHOR ENDS-->

	<!--IMPORTANT-->
	<!-- INCLUDE THE FOOTER AT THE END -->
	<?php
endif;
include ($footer);
?>
<canvas id="bg_hero_blur" style="display:none"></canvas>
<script type="text/javascript" src="<?php echo $siteUrl; ?>scripts/jquery-2.1.4.min.js"></script>
<script src="<?php echo $siteUrl; ?>scripts/jquery.magnific-popup.min.js"></script>
<script src="<?php echo $siteUrl; ?>scripts/jquery.sticky.min.js"></script>
<script src="<?php echo $siteUrl; ?>scripts/highlight/highlight.pack.js"></script>
<script src="<?php echo $siteUrl; ?>scripts/StackBlur.js"></script>
<script src="<?php echo $siteUrl; ?>scripts/menu.navigation.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		<?php if ($addon_already_liked == true): ?>
		$('#like_count').html('<?php echo $lang['addon_30']; ?>');
		$('#like_count').addClass('btn_red');
		<?php endif; ?>

		blurDo(); //activate blurry bg

		$('#screenshot_all').magnificPopup({
			delegate: 'a', // child items selector, by clicking on it popup will open
			type: 'image',
			verticalFit: true,
			removalDelay: 400,
			mainClass: 'mfp-fade',
			fixedContentPos: true,
			fixedBgPos: true,
			gallery: {enabled: true},
			callbacks: {
				open: function () {
					//The plugin has some issue with the scrollbar, this little hack should solve the issue
					$("html").css("margin", "0px")
				}
			}
		});

		/* Code blocks that do not have code type mentioned we will simply use "CODE" to display*/
		$("pre > code").not('[lang-rel]')
				.each(function () {
					$(this).attr('lang-rel', 'code');
				});

		hljs.initHighlightingOnLoad();
	});

	function blurDo() {
		var bg_blur = document.getElementById("bg_hero_blur");
		var ctx = bg_blur.getContext("2d");
		var img = new Image();
		img.setAttribute('crossOrigin', 'anonymous');
		img.src = "<?php echo $data['thumbnail']; ?>";
		img.onload = function () {
			ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, bg_blur.width, bg_blur.height);// draw image
			stackBlurCanvasRGB("bg_hero_blur", 0, 0, bg_blur.width, bg_blur.width, 40);
			var $blurryData = bg_blur.toDataURL();
			$('#bg_image').attr('style', 'background-image: url("' + $blurryData + '");');
		}
	}

	function rate(id) {
		hideNotification();
		$.ajax({
			url: "<?php echo $siteUrl; ?>includes/addons.tasks.php",
			cache: false,
			type: "POST",
			data: {id: "" + id + ""}
		}).done(function (data) {
			notificationCallback(data);
		}).fail(function (jqXHR, textStatus, errorThrown) {
			showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "error", "red_color");
		})
	}

	/*
	 Anonymous function declaration for rating updates
	 */

	var remove_rating = function () {
		var current_love_count = parseInt($('#like_count').attr('data-like-count'));
		var updated_love_count = current_love_count - 1;
		$('#like_count').attr('data-like-count', updated_love_count);
		$('#like_count').html('<?php echo $lang['addon_25']; ?>');
		$('#like_count').removeClass('btn_red');
	}

	var add_rating = function () {
		var current_love_count = parseInt($('#like_count').attr('data-like-count'));
		var updated_love_count = current_love_count + 1;
		$('#like_count').attr('data-like-count', updated_love_count);
		$('#like_count').html('<?php echo $lang['addon_30']; ?>');
		$('#like_count').addClass('btn_red');
	}

</script>
<?php
include_once $siteRoot . 'includes/system.notification.script.php';
?>
</body>
</html>