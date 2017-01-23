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

include_once $_SERVER['DOCUMENT_ROOT'] . '/app/functions.php';
include_once $link['root'].'classes/Addon.php';

if(isset($_POST['id'])) {
	if (ctype_digit($_POST['id']) && isset($_POST['rate'])) {
		$addon = new Addon();
		$addon->rate();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $addon_data['addon_title']; ?> - <?php echo $type_blob; ?> | <?php echo $lang['mb']; ?></title>
	<meta name="description" content="<?php echo $addon_data['short_description']; ?>">
	<!-- keyword meta tag is obsolete, google does not use it, but some
	search engine still use it, so for legacy support it is included -->
	<meta name="keywords" content="<?php echo implode(",", $addon_data['tags']); ?>, musicbee, customizable, skin, free, plugin, download">

	<!-- Opensearch-->
	<link rel="search" type="application/opensearchdescription+xml" href="<?php echo $link['url']; ?>opensearch.xml" title="<?php echo $lang['addon_opensearch_title']; ?>"/>

	<!--include common meta tags and stylesheets -->
	<?php include $link['root'].'includes/header.template.php'; ?>

	<link rel="stylesheet" href="<?php echo GetStyleDir(); ?>magnific-popup.css">
	<!-- Used for slider animation -->
	<link rel="stylesheet" href="<?php echo GetStyleDir(); ?>animate.css">
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
<!-- addon page INFO SECTION STARTS -->
<?php
/**
 * If the addon is soft deleted show error only!
 */
if($addon_data['status'] == "3" && !$mb['user']['can_mod']): ?>
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

	<!-- Search engine schema starts -->
	<div itemscope itemtype="http://schema.org/SoftwareApplication" style="display: none;">
		<data itemprop="name"><?php echo $addon_data['addon_title']; ?></data>
		<data itemprop="description"><?php echo $addon_data['short_description']; ?></data>
		<data itemprop="dateModified"><?php echo $addon_data['update_date']; ?></data>
		<data itemprop="datePublished"><?php echo $addon_data['publish_date']; ?></data>
		<data itemprop="author"><?php echo $addon_data['membername']; ?></data>
		<data itemprop="discussionUrl"><?php echo $addon_data['support_forum']; ?></data>
		<data itemprop="keywords"><?php echo implode(",", $addon_data['tags']); ?></data>
		<data itemprop="thumbnailUrl"><?php echo $addon_data['thumbnail']; ?></data>
		<data itemprop="downloadUrl"><?php echo $link['redirect'].'?type=addon&id='.$addon_data['ID_ADDON'].'&r='.urlencode($addon_data['download_links']); ?></data>
		<data itemprop="softwareVersion"><?php echo $addon_data['addon_version']; ?></data>
		<data itemprop="softwareRequirements"><?php echo implode(", ", $addon_data['supported_mbversion']); ?></data>
		<data itemprop="operatingSystem"><?php echo $mb['musicbee_download']['stable']['supported_os']; ?></data>
		<data itemprop="applicationCategory"><?php echo $lang['addons']; ?></data>
		<data itemprop="applicationSubCategory"><?php echo $type_blob; ?></data>
		<data itemprop="applicationSuite"><?php echo $lang['mb']; ?></data>
	</div>
	<!-- Search engine schema ends -->


	<div id="bg_image" class="general_info">
		<!-- AddOn page navigation top menu -->
		<div class="secondery_nav addon_secondery_nav addon_page_nav" id="secondery_nav">
			<div class="secondery_nav_wrap">
				<?php echo addon_secondery_nav_generator($addon_data['category']); ?>
			</div>
		</div>
		<div id="overlay_bg" class="general_info_color">
			<div class="general_info_wrap">
				<div class="general_info_text">
					<h2 class="title">
						<?php echo $addon_data['addon_title']; ?>
					</h2>
					<?php if($addon_data['addon_version'] != null): ?>
						<i class="general_info_addon_version"><?php echo htmlspecialchars($addon_data['addon_version'], ENT_QUOTES, "UTF-8"); ?></i>
					<?php endif; ?>
					<?php if($addon_data['is_beta'] == 1): ?>
						<p class="small_info beta"><?php echo $lang['addon_38']; ?></p>
					<?php endif; ?>

					<div class="general_info_addon_meta">
						<p><?php echo $lang['addon_15']; ?>
							<a href="<?php echo htmlspecialchars(addon_author_url_generator($addon_data['membername']), ENT_QUOTES, "UTF-8"); ?>"><b><?php echo $addon_data['membername']; ?></b></a>
						</p>
						<p>
							<?php echo $lang['addon_36']; ?>
							<b>
								<?php echo ($addon_data['supported_mbversion']) ? implode(", ", $addon_data['supported_mbversion']) : $lang['addon_37']; ?>
							</b>
						</p>
					</div>

					<p class="description"><?php echo $addon_data['short_description']; ?></p>
				</div>
				<div class="general_info_icon_wrap">
					<div class="general_info_icon" style='background-image: url("<?php echo htmlspecialchars(Format::imgurResizer($addon_data['thumbnail'], "m"), ENT_QUOTES, "UTF-8"); ?>");'></div>
				</div>
				<?php if(!empty(trim($addon_data['important_note']))): ?>
					<div class="general_info_sidenote">
						<p>
							<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp; <?php echo $addon_data['important_note']; ?>
						</p>
					</div>
				<?php endif; ?>

				<div class="general_info_downloadlink">

					<?php if($addon_data['status'] == 2): ?>
						<span class="show_info">
						<h3><?php echo $lang['addon_39']; ?></h3>
						<p class="description"><?php echo $lang['addon_40']; ?></p>
					</span>
					<?php elseif($addon_data['status'] != 1): ?>
						<span class="show_info danger">
							<h3><?php echo $lang['addon_34']; ?></h3>
							<p class="description"><?php echo $lang['addon_35']; ?></p>
						</span>
					<?php endif; ?>


					<?php if($addon_data['status'] != 2): ?>
						<a href="<?php echo $link['redirect'].'?type=addon&id='.$addon_data['ID_ADDON'].'&r='.urlencode($addon_data['download_links']); ?>" class="btn btn_blue" target="_blank">
							<i class="fa fa-download"></i>&nbsp;&nbsp;<?php echo $lang['download']; ?>
						</a>
						<?php if(!empty(trim($addon_data['support_forum']))): ?>
							<a href="<?php echo $addon_data['support_forum']; ?>" class="btn">
								<i class="fa fa-support"></i>&nbsp;&nbsp;<?php echo $lang['support_forum']; ?>
							</a>
						<?php endif; ?>
						<?php if(!empty(trim($addon_data['readme_content_html']))): ?>
							<a href="#readme" class="btn btn_grey" onclick="gotoReadme(event);">
								<i class="fa fa-info"></i>&nbsp;&nbsp;<?php echo $lang['see_readme']; ?>
							</a>
						<?php endif; ?>
						<a id="like_count" href="javascript:void(0)" class="btn btn_yellow like_btn"
						   onclick="rate('<?php echo $addon_data['ID_ADDON']; ?>')"
						   data-like-count="<?php echo Format::numberFormatSuffix($addon_data['likesCount']); ?>">
							<?php echo $lang['like']; ?>
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
				foreach($addon_data['image_links'] as $key => $img) {
					echo '<a class="screenshot_zoom_click" href="', htmlspecialchars($img, ENT_QUOTES, "UTF-8"), '">
						<div class="screenshot_wrapper" style=\'background-image:url("'.htmlspecialchars(Format::imgurResizer($img, "m"), ENT_QUOTES, "UTF-8").'")\'></div>
					</a>';
				}
				?>
			</ul>
		</div>
	</div>
	<!-- SLIDER ENDS-->



	<!-- WORD FROM AUTHOR STARTS -->
	<div class="addon_similar readme_markdown_bg" id="readme">
		<div class="addon_similar_wrap readme_markdown_wrap col_2_1">

			<?php if(!empty(trim($addon_data['readme_content_html']))): ?>
				<div class="addon_similar_inline_wrap">
					<div class="inline_wrap">
						<h2><?php echo $lang['addon_21']; ?></h2>
						<div id="readme_markdown" class="markdownView dark">
							<?php echo $addon_data['readme_content_html']; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="addon_similar_inline_wrap">
				<div class="inline_wrap extra_info">
					<div id="readme_markdown" >
						<table>
							<tbody>
							<tr>
								<td>
									<?php echo $lang['addon_10']; ?>
								</td>
								<td>
									<?php echo $addon_data['addon_title']; ?>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $lang['addon_9']; ?>
								</td>
								<td>
									<?php echo $addon_data['membername']; ?>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $lang['addon_8']; ?>
								</td>
								<td>
									<?php echo $addon_data['addon_version']; ?>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $lang['addon_11']; ?>
								</td>
								<td>
									<?php echo $type_blob; ?>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $lang['addon_17']; ?>
								</td>
								<td>
									<?php echo $addon_data['publish_date']; ?>
								</td>
							</tr>
							<?php if(null != $addon_data['update_date']): ?>
								<tr>
									<td>
										<?php echo $lang['addon_16']; ?>
									</td>
									<td>
										<?php echo $addon_data['update_date']; ?>
									</td>
								</tr>
							<?php endif; ?>
							<tr>
								<td>
									<?php echo $lang['addon_36']; ?>
								</td>
								<td>
									<?php echo implode(", ", $addon_data['supported_mbversion']); ?>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- WORD FROM AUTHOR ENDS-->
	<!-- tags STARTS -->
	<div class="addon_similar">
		<div class="addon_similar_wrap tags">
			<?php if(!empty($addon_data['tags'][0])): ?>
				<?php echo $lang['tags_icon']; ?>
				<?php foreach($addon_data['tags'] as $tags): ?>
					<a href="<?php echo $link['addon']['home'].'s/?q='.$tags; ?>"><?php echo $tags; ?></a>
				<?php endforeach; endif; ?>
		</div>
	</div>

	<!-- MORE FROM AUTHOR STARTS -->
	<div class="addon_similar alternate_bg">
		<div class="addon_similar_wrap">
			<h2><?php echo sprintf($lang['more_from_membername'],$addon_data['membername']); ?></h2>

			<?php echo addon_result_view_generator($from_author); ?>

			<div class="more_addon">
				<a class="btn btn_wireframe btn_wireframe_blue" href="<?php echo addon_author_url_generator($addon_data['membername']); ?>">
					<?php echo sprintf($lang['show_all_from_membername'], $addon_data['membername']); ?>
				</a>
			</div>

			<p class="license_attr"><?php echo $lang['addon_license']; ?>

			</p>

		</div>
	</div>
	<!-- MORE FROM AUTHOR ENDS-->

	<!--IMPORTANT-->
	<!-- INCLUDE THE FOOTER AT THE END -->
	<?php
endif;
include($footer);
?>
<canvas id="bg_hero_blur" style="display:none"></canvas>
<script src="<?php echo GetScriptDir(); ?>jquery.magnific-popup.min.js"></script>
<script src="<?php echo GetScriptDir(); ?>jquery.sticky.min.js"></script>
<script src="<?php echo GetScriptDir(); ?>highlight/highlight.pack.js"></script>
<script src="<?php echo GetScriptDir(); ?>StackBlur.js"></script>
<script type="text/javascript">
	$(document).ready(function () {

		<?php
		/**
		 * If the user already liked the addon change the like button show that it is already liked.
		 */
		if ($addon_data['user']['already_liked'] == true): ?>
		$('#like_count').html('<?php echo $lang['liked']; ?>');
		$('#like_count').addClass('btn_red');
		<?php endif; ?>

		blurDo(); //activate blurry bg

		$('#screenshot_all').magnificPopup({
			delegate: 'a',
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

		// Initialize code highlighting
		hljs.initHighlightingOnLoad();

		//add target="_blank" to each link element
		$("#readme_markdown a").not('[target]')
			.each(function () {
				$(this).attr('target', '_blank');
			});
	});

	function blurDo() {
		var bg_blur = document.getElementById("bg_hero_blur");
		var ctx = bg_blur.getContext("2d");
		var img = new Image();
		img.setAttribute('crossOrigin', 'anonymous');
		img.src = "<?php echo $addon_data['thumbnail']; ?>";
		img.onload = function () {
			ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, bg_blur.width, bg_blur.height);// draw image
			stackBlurCanvasRGB("bg_hero_blur", 0, 0, bg_blur.width, bg_blur.width, 40);
			var $blurryData = bg_blur.toDataURL();
			$('#bg_image').attr('style', 'background-image: url("' + $blurryData + '");');
		}
	}

	var gotoReadme = function (event) {
		event.preventDefault();
		$('html,body').animate({scrollTop: $('#readme').offset().top - '48'});
	}

	function rate(id) {
		hideNotification();
		$.ajax({
			url: "<?php echo currentUrl(); ?>",
			cache: false,
			type: "POST",
			data: {id: "" + id + "", rate: "true"}
		}).done(function (data) {
			notificationCallback(data);
		}).fail(function (jqXHR, textStatus, errorThrown) {
			showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "red_color");
		})
	}

	/*
	 Anonymous function declaration for rating updates
	 */

	var remove_rating = function () {
		var current_love_count = parseInt($('#like_count').attr('data-like-count'));
		var updated_love_count = current_love_count - 1;
		$('#like_count').attr('data-like-count', updated_love_count);
		$('#like_count').html('<?php echo $lang['like']; ?>');
		$('#like_count').removeClass('btn_red');
	}

	var add_rating = function () {
		var current_love_count = parseInt($('#like_count').attr('data-like-count'));
		var updated_love_count = current_love_count + 1;
		$('#like_count').attr('data-like-count', updated_love_count);
		$('#like_count').html('<?php echo $lang['liked']; ?>');
		$('#like_count').addClass('btn_red');
	}

</script>
<?php
include_once $link['root'].'includes/system.notification.script.php';
?>
</body>
</html>
