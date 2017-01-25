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


use App\Lib\Utility\Template;

$headerTemplate = new Template("header");
$headerTemplate->setData(
    "title",
    __("MusicBee - The Ultimate Music Manager and Player")
);
$headerTemplate->setData(
    "description",
    __("MusicBee makes it easy to organize, find, and play music files on your Windows computer, portable devices and on the web")
);
$headerTemplate->setData(
    "keywords",
    __("music, player, ultimate, best, customizable, skin, free, plugin")
);
$headerTemplate->setData("isFontHelperDisabled", false);

$link = path();
$headerTemplate->setData(
    "socialTags",
    <<<HTML
  	    <!--Social network tags for facebook and twitter -->
	    <meta property="og:title"           content="{$headerTemplate->getData('title')}"/>
	    <meta property="og:url"             content="{$link['url']}"/>
	    <meta property="og:image"           content="{$link['img-dir']}hero_img/mb-hero-interface-min.png">
	    <meta property="og:description"     content="{$headerTemplate->getData('description')}">   
	    <meta name="twitter:card"           content="summary">
	    <meta name="twitter:site"           content="@MusicBeePlayer">
	    <meta name="twitter:title"          content="{$headerTemplate->getData('title')}">
	    <meta name="twitter:description"    content="{$headerTemplate->getData('description')}">  
HTML
);

$headerTemplate->render();


$data = $template->getData("releasedata");
?>
	<div id="wrapper">
		<!-- BODY CONTENT -->
		<div id="main">
			<div id="main_panel">
				<div class="mb_landing_overlay mb_intro_top">
					<div class="overlay">
						<!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
        <?php
        include_once path('template-dir').'mainmenu.template.php';
        ?>
						<section class="mb_landing align_right">
							<div class="sub_content">
								<div class="hero_text_top">
									<div class="text_wrapper text_white">
										<h1><?php echo __("The Ultimate Music Manager and Player"); ?></h1>
										<h4><?php echo __("MusicBee makes it easy to manage, find, and play music files on your computer. MusicBee also supports podcasts, web radio stations and SoundCloud integration"); ?></h4>
									</div>
								</div>
								<div class="hero_img_top">
									<div class="hero_img_wrapper hero_img_topmost_wrap">
										<img src="<?php echo path('img-dir'); ?>hero_img/hero-img-top-min.png">
									</div>
								</div>
							</div>
							<div class="sub_content_bottom">
								<div class="sub_content hero_buttons">
									<h4><?php echo __("Get MusicBee, you will never go back. And it's free!"); ?></h4>
									<a href="<?php echo path('download'); ?>" class="btn btn_wireframe btn_wireframe_yellow">
										<h3><?php echo __("Download Now"); ?></h3>
										<p><?php echo sprintf(__("For %1\$s"), $data['supported_os']); ?></p>
									</a>
									<a
                                        id="feature_scroll"
                                        class="btn btn_wireframe btn_wireframe_blue"
                                        href="javascript:void(0)"
                                    >
										<h3><?php echo __("Check out features"); ?></h3>
										<p><?php echo __("See the best of MusicBee"); ?></p>
									</a>
								</div>
							</div>
						</section>
					</div>
				</div>


				<div class="mb_landing align_center" >
					<div class="sub_content" id="simple_powerful">
						<div class="hero_text_top text_black">
							<h2 data-sr="enter top"><?php echo __("Simple, Powerful, and Fast"); ?></h2>
							<h4 data-sr="enter bottom"><?php echo __("Play your music the way you want. Turn your computer into a music jukebox. Use auto-tagging to clean up your messy music library. Enjoy a great music experience with MusicBee."); ?></h4>
						</div>
						<div class="hero_img_top">
							<img src="<?php echo path('img-dir'); ?>hero_img/mb-hero-interface-min.png" data-sr="vFactor 0.2">
						</div>
					</div>
				</div>

				<div class="mb_quality_top mb_landing align_center">
					<div class="mb_landing overlay">
						<div class="sub_content">
							<h2 data-sr='move 24px'><?php echo __("Sound Quality Matters"); ?></h2>
							<h4 data-sr="enter bottom"><?php echo __("Whether you play your music on an audiophile setup or on a laptop, MusicBee is designed with features to fulfill all your needs."); ?></h4>

							<ul class="feature_box">
								<li>
									<div data-sr="vFactor 0.2">
										<p class="feature_ico">
											<i class="fa fa-sliders"></i>
										</p>
										<p>
            <?php echo __("Fine-tune the sound with the 10-band or 15-band Equalizer and DSP effects"); ?>
										</p>
									</div>
								</li>
								<li>
									<div data-sr="vFactor 0.2">
										<p class="feature_ico">
											<i class="fa fa-headphones"></i>
										</p>
										<p><?php echo __("Utilize high-end audio cards with WASAPI and ASIO support"); ?></p>
									</div>
								</li>
								<li>
									<div data-sr="vFactor 0.2">
										<p class="feature_ico">
											<i class="fa fa-play"></i>
										</p>
										<p><?php echo __("Listen to music without interruption with gapless playback"); ?></p>
									</div>
								</li>

								<li>
									<div data-sr="vFactor 0.2">
										<p class="feature_ico">
											<i class="fa fa-bullseye"></i>
										</p>
										<p>
            <?php echo __("Upmix stereo to 5.1 surround sound, or resample track to lower bitrate"); ?>
										</p>
									</div>
								</li>
								<li>
									<div data-sr="vFactor 0.2">
										<p class="feature_ico">
											<i class="fa fa-sort-amount-asc"></i>
										</p>
										<p><?php echo __("Use logarithmic volume scaling or normalize volume streaming"); ?></p>
									</div>
								</li>
								<li>
									<div data-sr="vFactor 0.2">
										<p class="feature_ico">
											<i class="fa fa-plug"></i>
										</p>
										<p><?php echo __("Even better, MusicBee supports some WinAmp plugins to enhance your music"); ?></p>
									</div>
								</li>
								<div id="clear"></div>
							</ul>
						</div>
					</div>
				</div>



				<section class="mb_landing align_right">
					<div class="sub_content">
						<div class="hero_text_top">
							<div class="text_wrapper text_black">
								<h1><?php echo __("Beautiful Skins"); ?></h1>
								<h4><?php echo __("Change the appearance of MusicBee by choosing from the included skins or download more from our Add-on section.<br/>Skins are a great way to personalize MusicBee to your liking.<br/><br/> You can also make your own skin and share it with others."); ?></h4>
							</div>
						</div>
						<div class="hero_img_top">
							<div class="hero_img_wrapper">
								<img src="<?php echo path('img-dir'); ?>hero_img/hero-img-skin-min.png">
							</div>
						</div>
					</div>
				</section>

				<section class="mb_landing align_left alternate_landing_row_bg">
					<div class="sub_content">
						<div class="hero_text_top">
							<div class="text_wrapper text_black">
								<h1><?php echo __("Sync with Devices"); ?></h1>
								<h4><?php echo __("Sync your music collection with devices you use. MusicBee supports playlist and podcast syncing, even supports audio books with 2 way syncing.<br/>Convert formats on the fly if your device does not support certain formats. <br/><br/>You can also sync your Android and Windows Phone (8.1+) devices."); ?></h4>
							</div>
						</div>
						<div class="hero_img_top">
							<div class="hero_img_wrapper">
								<img src="<?php echo path('img-dir'); ?>hero_img/hero-img-sync-min.png">
							</div>
						</div>
					</div>
				</section>

				<section class="mb_landing align_right bg_blue">
					<div class="sub_content">
						<div class="hero_text_top">
							<div class="text_wrapper text_white">
								<h1><?php echo __("Groove Music Support"); ?></h1>
								<h4><?php echo __("MusicBee has native support for Groove Music (formerly Xbox Music). You can stream directly from MusicBee or add to your existing playlist, get song recommendations from the vast Groove catalog.<br/><br/>Want to listen to a song before buy? You can listen to preview. "); ?></h4>
							</div>
						</div>
						<div class="hero_img_top">
							<div class="hero_img_wrapper">
								<img src="<?php echo path('img-dir'); ?>hero_img/hero-img-groove-min.png">
							</div>
						</div>
					</div>
				</section>

				<section class="mb_landing align_left">
					<div class="sub_content">
						<div class="hero_text_top">
							<div class="text_wrapper text_black">
								<h1><?php echo __("last.fm, CD Ripping, Tagging tools.... plus more!"); ?></h1>
								<h4><?php echo __("MusicBee packs a comprehensive set of features to make your music experience better. <br/><br/>Yet it is <b>one of the most lightweight player</b> using about 25-70 MB ram* with skins and add-ons, and packs all of these under 10 MB!"); ?></h4>
								<p class="disclaimer"><?php echo __("<br/><br/>*Tested with MusicBee 3 with a library of 200 albums, sized around 3GB."); ?></p>
							</div>
						</div>
						<div class="hero_img_top">
							<div class="hero_img_wrapper">
								<img src="<?php echo path('img-dir'); ?>hero_img/mb_tag_feature.png">
							</div>
						</div>
					</div>
				</section>

				<section class="mb_landing align_right alternate_landing_row_bg">
					<div class="sub_content">
						<div class="hero_text_top">
							<div class="text_wrapper text_black">
								<h1><?php echo __("The Best.... rated by reviewers and users"); ?></h1>
								<h4><?php echo __("MusicBee is rated one of the best music managers and players available for Windows. It packs features that will WOW you. <br/><br/>We have a dedicated thread for users to share their experience or check reviews from trusted sources.<br/><br/>Start using MusicBee today. You will never go back."); ?></h4>
							</div>
						</div>
						<div class="hero_img_top">
							<div class="hero_img_wrapper">
								<img src="<?php echo path('img-dir'); ?>hero_img/hero-img-review.png">
							</div>
						</div>
					</div>
				</section>


				<div class="mb_more_top mb_landing_overlay align_center">
					<div class="overlay">
						<div class="sub_content">
							<h4><?php echo __("Get MusicBee and enhance your music experience"); ?></h4>
							<a href="<?php echo path('download'); ?>" class="btn btn_wireframe btn_wireframe_blue">
								<h3><?php echo __("Download Now"); ?></h3>
								<p><?php echo sprintf(__("For %1\$s"), $data['supported_os']); ?></p>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<!--IMPORTANT-->
<!-- INCLUDE THE FOOTER AT THE END -->
<?php
//include_once $footer;
?>
<script src="<?php echo path('js-dir'); ?>scrollreveal/scrollreveal.js"></script>
<script src="<?php echo path('js-dir'); ?>jquery.sticky.min.js"></script>
<script src="<?php echo path('js-dir'); ?>mb_common.js"></script>
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
		origin   : "bottom",
		distance : "50px",
		viewFactor: "0.1",
		duration : 1000,
		delay    : 400,
		scale    : 0,
		mobile   : false
	};

	window.sr = ScrollReveal()
			.reveal('.text_wrapper', text_wrapper)
			.reveal('.hero_text_top h1', hero_title_reveal)
			.reveal('.hero_buttons', download)
			.reveal('img', img)
			.reveal('.hero_img_top img', hero_img);

	$("#feature_scroll").click(function () {
        $('html,body').animate({scrollTop: $('#simple_powerful').offset().top});
    });


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
