<?php
/**
 * Copyright (c) AvikB, some rights reserved.
 * Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 * Spelling mistakes and fixes from community members.
 */

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $lang['home_title']; ?></title>
	<meta name="description" content="<?php echo $lang['home_desc']; ?>">

	<!-- keyword meta tag is obsolete, google does not use it, but some
	search engine still use it, so for legacy support it is included -->
	<meta name="keywords" content="musicbee, music, player, ultimate, best, customizable, skin, free, plugin">

	<!--include common meta tags and stylesheets -->
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/meta&styles.php'; ?>

	<!--Social network tags for facebook and twitter -->
	<meta property="og:title" content="<?php echo $lang['home_title']; ?>"/>
	<meta property="og:url" content="<?php echo $link['url']; ?>"/>
	<meta property="og:image" content="<?php echo $link['url']; ?>img/mb_big.png">
	<meta property="og:description" content="<?php echo $lang['home_desc']; ?>">

	<meta name="twitter:card" content="summary">
	<meta name="twitter:site" content="@MusicBeePlayer">
	<meta name="twitter:title" content="MusicBee - Music Manager and Player">
	<meta name="twitter:description" content="<?php echo $lang['home_desc']; ?>">

	<!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
	<?php include $link['root'].'includes/font.helper.php'; ?>
</head>
<body>
<div id="indexBackground">
	<div id="wrapper">
		<!-- BODY CONTENT -->
		<div id="main">
			<div id="main_panel">
				<div class="mb_landing_overlay mb_intro_top">
					<div class="overlay">
						<!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
						<?php
						include($mainmenu);
						?>
						<section class="mb_landing align_right">
							<div class="sub_content">
								<div class="hero_text_top">
									<div class="text_wrapper text_white">
										<h1><?php echo $lang['home_2']; ?></h1>
										<h4><?php echo $lang['home_3']; ?></h4>
									</div>
								</div>
								<div class="hero_img_top">
									<div class="hero_img_wrapper hero_img_topmost_wrap">
										<img src="<?php echo $link['url']; ?>img/hero_img/hero-img-top-min.png">
									</div>
								</div>
							</div>
							<div class="sub_content_bottom">
								<div class="sub_content hero_buttons">
									<h4><?php echo $lang['home_4']; ?></h4>
									<a href="<?php echo $link['download']; ?>" class="btn btn_wireframe btn_wireframe_yellow">
										<h3><?php echo $lang['home_5']; ?></h3>
										<p><?php echo sprintf($lang['for_os'], $mb['musicbee_download']['stable']['supported_os']); ?></p>
									</a>
									<a class="btn btn_wireframe btn_wireframe_blue" onclick="$('html,body').animate({scrollTop: $('#simple_powerful').offset().top});" href="javascript:void(0)">
										<h3><?php echo $lang['home_6']; ?></h3>
										<p><?php echo $lang['home_7']; ?></p>
									</a>
								</div>
							</div>
						</section>
					</div>
				</div>


				<div class="mb_landing align_center" >
					<div class="sub_content" id="simple_powerful">
						<div class="hero_text_top text_black">
							<h2 data-sr="enter top"><?php echo $lang['home_9']; ?></h2>
							<h4 data-sr="enter bottom"><?php echo $lang['home_10']; ?></h4>
						</div>
						<div class="hero_img_top">
							<img src="<?php echo $link['url']; ?>img/hero_img/mb-hero-interface-min.png" data-sr="vFactor 0.2">
						</div>
					</div>
				</div>

				<div class="mb_quality_top mb_landing align_center">
					<div class="mb_landing overlay">
						<div class="sub_content">
							<h2 data-sr='move 24px'><?php echo $lang['home_11']; ?></h2>
							<h4 data-sr="enter bottom"><?php echo $lang['home_12']; ?></h4>

							<ul class="feature_box">
								<li>
									<div data-sr="vFactor 0.2">
										<p class="feature_ico">
											<i class="fa fa-sliders"></i>
										</p>
										<p>
											<?php echo $lang['home_13']; ?>
										</p>
									</div>
								</li>
								<li>
									<div data-sr="vFactor 0.2">
										<p class="feature_ico">
											<i class="fa fa-headphones"></i>
										</p>
										<p><?php echo $lang['home_14']; ?></p>
									</div>
								</li>
								<li>
									<div data-sr="vFactor 0.2">
										<p class="feature_ico">
											<i class="fa fa-play"></i>
										</p>
										<p><?php echo $lang['home_15']; ?></p>
									</div>
								</li>

								<li>
									<div data-sr="vFactor 0.2">
										<p class="feature_ico">
											<i class="fa fa-bullseye"></i>
										</p>
										<p>
											<?php echo $lang['home_38']; ?>
										</p>
									</div>
								</li>
								<li>
									<div data-sr="vFactor 0.2">
										<p class="feature_ico">
											<i class="fa fa-sort-amount-asc"></i>
										</p>
										<p><?php echo $lang['home_39']; ?></p>
									</div>
								</li>
								<li>
									<div data-sr="vFactor 0.2">
										<p class="feature_ico">
											<i class="fa fa-plug"></i>
										</p>
										<p><?php echo $lang['home_40']; ?></p>
									</div>
								</li>
								<div id="clear"></div>
							</ul>
						</div>

						<!--
						<div class="sub_content_bottom">
							<div class="sub_content hero_buttons">
								<h4><?php echo $lang['home_16']; ?></h4>
								<a href="" class="btn btn_wireframe btn_wireframe_blue">
									<h3><?php echo $lang['home_17']; ?></h3>
								</a>
							</div>
						</div>
						-->
					</div>
				</div>



				<section class="mb_landing align_right">
					<div class="sub_content">
						<div class="hero_text_top">
							<div class="text_wrapper text_black">
								<h1><?php echo $lang['home_18']; ?></h1>
								<h4><?php echo $lang['home_19']; ?></h4>
							</div>
						</div>
						<div class="hero_img_top">
							<div class="hero_img_wrapper">
								<img src="<?php echo $link['url']; ?>img/hero_img/hero-img-skin-min.png">
							</div>
						</div>
					</div>
				</section>

				<section class="mb_landing align_left alternate_landing_row_bg">
					<div class="sub_content">
						<div class="hero_text_top">
							<div class="text_wrapper text_black">
								<h1><?php echo $lang['home_20']; ?></h1>
								<h4><?php echo $lang['home_21']; ?></h4>
							</div>
						</div>
						<div class="hero_img_top">
							<div class="hero_img_wrapper">
								<img src="<?php echo $link['url']; ?>img/hero_img/hero-img-sync-min.png">
							</div>
						</div>
					</div>
				</section>

				<section class="mb_landing align_right bg_blue">
					<div class="sub_content">
						<div class="hero_text_top">
							<div class="text_wrapper text_white">
								<h1><?php echo $lang['home_31']; ?></h1>
								<h4><?php echo $lang['home_32']; ?></h4>
							</div>
						</div>
						<div class="hero_img_top">
							<div class="hero_img_wrapper">
								<img src="<?php echo $link['url']; ?>img/hero_img/hero-img-groove-min.png">
							</div>
						</div>
					</div>
				</section>

				<section class="mb_landing align_left">
					<div class="sub_content">
						<div class="hero_text_top">
							<div class="text_wrapper text_black">
								<h1><?php echo $lang['home_35']; ?></h1>
								<h4><?php echo $lang['home_36']; ?></h4>
								<p class="disclaimer"><?php echo $lang['home_37']; ?></p>
							</div>
						</div>
						<div class="hero_img_top">
							<div class="hero_img_wrapper">
								<img src="<?php echo $link['url']; ?>img/hero_img/mb_tag_feature.png">
							</div>
						</div>
					</div>
				</section>

				<section class="mb_landing align_right alternate_landing_row_bg">
					<div class="sub_content">
						<div class="hero_text_top">
							<div class="text_wrapper text_black">
								<h1><?php echo $lang['home_33']; ?></h1>
								<h4><?php echo $lang['home_34']; ?></h4>
							</div>
						</div>
						<div class="hero_img_top">
							<div class="hero_img_wrapper">
								<img src="<?php echo $link['url']; ?>img/hero_img/hero-img-review.png">
							</div>
						</div>
					</div>
				</section>


				<div class="mb_more_top mb_landing_overlay align_center">
					<div class="overlay">
						<div class="sub_content">
							<h4><?php echo $lang['home_29']; ?></h4>
							<a href="<?php echo $link['download']; ?>" class="btn btn_wireframe btn_wireframe_blue">
								<h3><?php echo $lang['home_5']; ?></h3>
								<p><?php echo sprintf($lang['for_os'], $mb['musicbee_download']['stable']['supported_os']); ?></p>
							</a>
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
<script src="<?php echo $link['url']; ?>scripts/scrollReveal.min.js"></script>
<script src="<?php echo $link['url']; ?>scripts/jquery.sticky.min.js"></script>
<script src="<?php echo $link['url']; ?>scripts/mb_common.js"></script>
<script type="text/javascript">
	//initialize scroll reveal
	var hero_title_reveal = {
		origin   : "top",
		distance : "3vw",
		duration : 700,
		delay 	 : 0,
		scale    : 1.05
	};

	var text_wrapper = {
		origin   : "bottom",
		distance : "2vw",
		duration : 500,
		delay    : 300,
		scale    : 1
	};

	var download = {
		origin   : "top",
		distance : "1vw",
		duration : 600,
		delay    : 400,
		scale    : 0
	};
	var img = {
		origin   : "top",
		distance : "2vw",
		duration : 1000,
		delay    : 50,
		scale    : 1
	};
	var hero_img = {
		origin   : "right",
		distance : "30px",
		viewFactor : "0.5",
		duration : 1000,
		delay    : 50,
		scale    : .9,
		mobile   : false
	};

	window.sr = ScrollReveal()
			.reveal('.text_wrapper', text_wrapper)
			.reveal('.hero_text_top h1', hero_title_reveal)
			.reveal('.hero_buttons', download)
			.reveal('img', img)
			.reveal('.hero_img_top img', hero_img);


	$primary_nav_break = 801;
	var $nav_bar = $("#main_menu");

	function primary_nav_sticky() {
		if (window.innerWidth < $primary_nav_break) {
			$nav_bar.unstick();
		} else {
			$nav_bar.unstick();
			$nav_bar.sticky({topSpacing: 0});
		}
	}

	$(window).resize(function(e) {
		primary_nav_sticky();
	});

	$(document).ready(function(){
		primary_nav_sticky();
	});

</script>
</body>
</html>
