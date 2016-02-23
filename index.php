<?php
/**
 * Copyright (c) AvikB, some rights reserved.
 * Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 * Spelling mistakes and fixes from phred and other community memebers.
 */

include $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $lang['IP_TITLE']; ?></title>
        <meta name="description" content="<?php echo $lang['IP_DESCRIPTION']; ?>">
        <!-- keyword meta tag is obsolete, google does not use it, but some
        search engine still use it, so for legacy support it is included -->
        <meta name="keywords" content="musicbee, music, player, ultimate, best, customizable, skin, free, plugin">
        <!--include common meta tags and stylesheets -->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/meta&styles.php'; ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>styles/MusicBeeIndex.css">
        <!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/font.helper.php'; ?>
        <!--Social network tags for facebook and twitter -->
        <meta property="og:title" content="">
        <meta property="og:image" content="<?php echo $siteUrl; ?>img/mb_big.png">
        <meta property="og:description" content="<?php echo $lang['IP_DESCRIPTION']; ?>">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:site" content="@MusicBeePlayer"> 
        <meta name="twitter:title" content="MusicBee - Music Manager and Player">
        <meta name="twitter:description" content="<?php echo $lang['IP_DESCRIPTION']; ?>">
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
                        <div class="content mb_intro_top">
                            <div class="overlay">
                                    <div class="sub_content" style="">
                                        <div class="hero_text_top">
                                            <!-- <h1 class="hero_title"><?php echo $lang['home_1']; ?></h1> -->
                                            <div class="intro">
                                                <h1><?php echo $lang['home_2']; ?></h1>
                                                <h4><?php echo $lang['home_3']; ?></h4> 
                                            </div>
                                        </div>
                                        <div class="hero_img_top">
                                            <img src="img/hero-img-top-min.png">
                                        </div>
                                    </div>
                                    <div class="content mb_message_top">
                                        <div class="sub_content top_download_option hero_download_buttons">
                                            <h4><?php echo $lang['home_4']; ?></h4>
                                            <a href="<?php echo $link['download']; ?>" class="yellow_btn_big">
                                                <h3><?php echo $lang['home_5']; ?></h3>
                                                <p class="second_line"><?php echo $lang['home_8']; ?> <?php echo $release['stable']['os']; ?></p>
                                            </a>
                                            <a class="blue_btn_big" onclick="$('html,body').animate({scrollTop: $('#simple_powerful').offset().top});" href="javascript:void(0)">
                                                <h3><?php echo $lang['home_6']; ?></h3>
                                                <p class="second_line"><?php echo $lang['home_7']; ?></p>
                                            </a>
                                        </div>
                                    </div>
                            </div>
                        </div>


                        <div class="content mb_feature_top" >
                            <div class="sub_content" id="simple_powerful">
                                <h2 data-sr="enter top"><?php echo $lang['home_9']; ?></h2>
                                <h4 data-sr="enter bottom"><?php echo $lang['home_10']; ?></h4>
                                <img src="./img/mb_default_feature.jpg" data-sr="vFactor 0.2">
                            </div>
                        </div>
                        <div class="content mb_quality_top">
                            <div class="overlay_grad_quality_top">
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
                                        <div id="clear"></div>
                                    </ul>
                                </div>
                                <div class="content mb_message_top">
                                    <div class="sub_content top_download_option">
                                        <h4><?php echo $lang['home_16']; ?></h4>
                                        <a class="blue_btn_big" href="#">
                                            <h4><?php echo $lang['home_17']; ?></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content mb_skin_top">
                            <div class="sub_content" >
                                <h2 data-sr="enter top"><?php echo $lang['home_18']; ?></h2>
                                <h4 data-sr="enter bottom"><?php echo $lang['home_19']; ?></h4>
                                <img src="./img/mb_skin_feature.jpg" data-sr="vFactor 0.2">
                            </div>
                        </div>
                        <div class="content mb_sync_top">
                            <div class="sub_content" data-sr='move 24px'>
                                <h2><?php echo $lang['home_20']; ?></h2>
                                <h4><?php echo $lang['home_21']; ?></h4>
                            </div>
                        </div>
                        <div class="content mb_tag_top">
                            <div class="sub_content">
                                <h2 data-sr="enter top"><?php echo $lang['home_22']; ?></h2>
                                <h4 data-sr="enter bottom"><?php echo $lang['home_23']; ?>
                                </h4>
                                <img src="./img/mb_tag_feature.jpg" data-sr="vFactor 0.2">
                            </div>
                        </div>
                        <div class="content mb_more_top">
                            <div class="overlay_grad_more_top">
                                <div class="sub_content">
                                    <h2 data-sr='move 24px'><?php echo $lang['home_24']; ?></h2>
                                    <h4 data-sr="enter bottom"><?php echo $lang['home_25']; ?></h4>
                                    <ul class="feature_box">
                                        <li>
                                            <div data-sr="vFactor 0.2">
                                                <p class="feature_ico">
                                                    <i class="fa fa-lastfm"></i>
                                                </p>
                                                <p><?php echo $lang['home_26']; ?>
                                                </p>
                                            </div>
                                        </li>
                                        <li>
                                            <div data-sr="vFactor 0.2">
                                                <p class="feature_ico">
                                                    <i class="fa fa-puzzle-piece"></i>
                                                </p>
                                                <p><?php echo $lang['home_27']; ?>
                                                </p>
                                            </div>
                                        </li>
                                        <li>
                                            <div data-sr="vFactor 0.2">
                                                <p class="feature_ico">
                                                    <i class="fa fa-circle-o-notch"></i>
                                                </p>
                                                <p><?php echo $lang['home_28']; ?>
                                                </p>
                                            </div>
                                        </li>
                                        <div id="clear"></div>
                                    </ul>
                                </div>
                                <div class="content mb_message_top">
                                    <div class="sub_content top_download_option">
                                        <h4><?php echo $lang['home_29']; ?></h4>
                                        <a href="<?php echo $link['download']; ?>" class="yellow_btn_big">
                                            <h3><?php echo $lang['home_5']; ?></h3>
                                            <p class="second_line"><?php echo $lang['home_8']; ?> <?php echo $release['stable']['os']; ?></p>
                                        </a>
                                    </div>
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
	<script src="<?php echo $siteUrl; ?>scripts/jquery-2.1.4.min.js"></script>
	<script src="<?php echo $siteUrl; ?>scripts/scrollReveal.min.js"></script>
    <script src="<?php echo $siteUrl; ?>scripts/mb_common.js"></script>
	<script type="text/javascript">
		//initialize scroll reveal
		var hero_title_reveal = {
			origin   : "top",
			distance : "3vw",
			duration : 700,
			delay 	 : 0,
			scale    : 1.05
		};

		var intro = {
			origin   : "bottom",
			distance : "2vw",
			duration : 900,
			delay    : 600,
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

		window.sr = ScrollReveal()
		.reveal('.hero_title', hero_title_reveal)
		.reveal('.intro', intro)
		.reveal('.top_download_option', download)
		.reveal('img', img);

	</script>
</body>
</html>