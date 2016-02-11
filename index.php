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
                                <div class="shadow_wrap">
                                    <div class="sub_content text_left" style="">
                                        <h1 class="hero_title"><?php echo $lang['149']; ?></h1>
                                        <div class="intro">
                                            <h2><?php echo $lang['150']; ?></h2>
                                            <!-- <h4><?php echo $lang['151']; ?></h4> -->
                                        </div>
                                    </div>
                                    <div class="content mb_message_top">
                                        <div class="sub_content top_download_option text_left">
                                            <h4><?php echo $lang['152']; ?></h4>
                                            <a href="<?php echo $link['download']; ?>" class="yellow_btn_big">
                                                <h3><?php echo $lang['153']; ?></h3>
                                                <p class="second_line"><?php echo $lang['156']; ?> <?php echo $release['stable']['os']; ?></p>
                                            </a>
                                            <a class="blue_btn_big" onclick="$('html,body').animate({scrollTop: $('#simple_powerful').offset().top});" href="javascript:void(0)">
                                                <h3><?php echo $lang['154']; ?></h3>
                                                <p class="second_line"><?php echo $lang['155']; ?></p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="content mb_feature_top" >
                            <div class="sub_content" id="simple_powerful">
                                <h2 data-sr="enter top"><?php echo $lang['157']; ?></h2>
                                <h4 data-sr="enter bottom"><?php echo $lang['158']; ?></h4>
                                <img src="./img/mb_default_feature.jpg" data-sr="vFactor 0.2">
                            </div>
                        </div>
                        <div class="content mb_quality_top">
                            <div class="overlay_grad_quality_top">
                                <div class="sub_content">
                                    <h2 data-sr='move 24px'><?php echo $lang['159']; ?></h2>
                                    <h4 data-sr="enter bottom"><?php echo $lang['160']; ?></h4>

                                    <ul class="feature_box">
                                        <li>
                                            <div data-sr="vFactor 0.2">
                                                <p class="feature_ico">
                                                    <i class="fa fa-sliders"></i>
                                                </p>
                                                <p>
                                                    <?php echo $lang['161']; ?>
                                                </p>
                                            </div>
                                        </li>
                                        <li>
                                            <div data-sr="vFactor 0.2">
                                                <p class="feature_ico">
                                                    <i class="fa fa-headphones"></i>
                                                </p>
                                                <p><?php echo $lang['162']; ?></p>
                                            </div>
                                        </li>
                                        <li>
                                            <div data-sr="vFactor 0.2">
                                                <p class="feature_ico">
                                                    <i class="fa fa-play"></i>
                                                </p>
                                                <p><?php echo $lang['163']; ?></p>
                                            </div>
                                        </li>
                                        <div id="clear"></div>
                                    </ul>
                                </div>
                                <div class="content mb_message_top">
                                    <div class="sub_content top_download_option">
                                        <h4><?php echo $lang['164']; ?></h4>
                                        <a class="blue_btn_big" href="#">
                                            <h4><?php echo $lang['165']; ?></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content mb_skin_top">
                            <div class="sub_content" >
                                <h2 data-sr="enter top"><?php echo $lang['166']; ?></h2>
                                <h4 data-sr="enter bottom"><?php echo $lang['167']; ?></h4>
                                <img src="./img/mb_skin_feature.jpg" data-sr="vFactor 0.2">
                            </div>
                        </div>
                        <div class="content mb_sync_top">
                            <div class="sub_content" data-sr='move 24px'>
                                <h2><?php echo $lang['168']; ?></h2>
                                <h4><?php echo $lang['169']; ?></h4>
                            </div>
                        </div>
                        <div class="content mb_tag_top">
                            <div class="sub_content">
                                <h2 data-sr="enter top"><?php echo $lang['170']; ?></h2>
                                <h4 data-sr="enter bottom"><?php echo $lang['171']; ?>
                                </h4>
                                <img src="./img/mb_tag_feature.jpg" data-sr="vFactor 0.2">
                            </div>
                        </div>
                        <div class="content mb_more_top">
                            <div class="overlay_grad_more_top">
                                <div class="sub_content">
                                    <h2 data-sr='move 24px'><?php echo $lang['172']; ?></h2>
                                    <h4 data-sr="enter bottom"><?php echo $lang['173']; ?></h4>
                                    <ul class="feature_box">
                                        <li>
                                            <div data-sr="vFactor 0.2">
                                                <p class="feature_ico">
                                                    <i class="fa fa-lastfm"></i>
                                                </p>
                                                <p><?php echo $lang['174']; ?>
                                                </p>
                                            </div>
                                        </li>
                                        <li>
                                            <div data-sr="vFactor 0.2">
                                                <p class="feature_ico">
                                                    <i class="fa fa-puzzle-piece"></i>
                                                </p>
                                                <p><?php echo $lang['175']; ?>
                                                </p>
                                            </div>
                                        </li>
                                        <li>
                                            <div data-sr="vFactor 0.2">
                                                <p class="feature_ico">
                                                    <i class="fa fa-circle-o-notch"></i>
                                                </p>
                                                <p><?php echo $lang['176']; ?>
                                                </p>
                                            </div>
                                        </li>
                                        <div id="clear"></div>
                                    </ul>
                                </div>
                                <div class="content mb_message_top">
                                    <div class="sub_content top_download_option">
                                        <h4><?php echo $lang['177']; ?></h4>
                                        <a href="<?php echo $link['download']; ?>" class="yellow_btn_big">
                                            <h3><?php echo $lang['153']; ?></h3>
                                            <p class="second_line"><?php echo $lang['156']; ?> <?php echo $release['stable']['os']; ?></p>
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


		//smooth scroll
		function ssc_init(){"use strict";if(document.body){var e=document.body,s=document.documentElement,c=window.innerHeight,t=e.scrollHeight;if(ssc_root=document.compatMode.indexOf("CSS")>=0?s:e,ssc_activeElement=e,ssc_initdone=!0,top!=self)ssc_frame=!0;else if(t>c&&(e.offsetHeight<=c||s.offsetHeight<=c)&&(ssc_root.style.height="auto",ssc_root.offsetHeight<=c)){var r=document.createElement("div");r.style.clear="both",e.appendChild(r)}ssc_fixedback||(e.style.backgroundAttachment="scroll",s.style.backgroundAttachment="scroll"),ssc_keyboardsupport&&ssc_addEvent("keydown",ssc_keydown)}}function ssc_scrollArray(e,s,c,t){if(t||(t=1e3),ssc_directionCheck(s,c),ssc_que.push({x:s,y:c,lastX:0>s?.99:-.99,lastY:0>c?.99:-.99,start:+new Date}),!ssc_pending){var r=function(){for(var o=+new Date,n=0,a=0,i=0;i<ssc_que.length;i++){var l=ssc_que[i],_=o-l.start,u=_>=ssc_animtime,d=u?1:_/ssc_animtime;ssc_pulseAlgorithm&&(d=ssc_pulse(d));var f=l.x*d-l.lastX>>0,m=l.y*d-l.lastY>>0;n+=f,a+=m,l.lastX+=f,l.lastY+=m,u&&(ssc_que.splice(i,1),i--)}if(s){var h=e.scrollLeft;e.scrollLeft+=n,n&&e.scrollLeft===h&&(s=0)}if(c){var p=e.scrollTop;e.scrollTop+=a,a&&e.scrollTop===p&&(c=0)}s||c||(ssc_que=[]),ssc_que.length?setTimeout(r,t/ssc_framerate+1):ssc_pending=!1};setTimeout(r,0),ssc_pending=!0}}function ssc_wheel(e){ssc_initdone||ssc_init();var s=e.target,c=ssc_overflowingAncestor(s);if(!c||e.defaultPrevented||ssc_isNodeName(ssc_activeElement,"embed")||ssc_isNodeName(s,"embed")&&/\.pdf/i.test(s.src))return!0;var t=e.wheelDeltaX||0,r=e.wheelDeltaY||0;t||r||(r=e.wheelDelta||0),Math.abs(t)>1.2&&(t*=ssc_stepsize/120),Math.abs(r)>1.2&&(r*=ssc_stepsize/120),ssc_scrollArray(c,-t,-r),e.preventDefault()}function ssc_keydown(e){var s=e.target,c=e.ctrlKey||e.altKey||e.metaKey;if(/input|textarea|embed/i.test(s.nodeName)||s.isContentEditable||e.defaultPrevented||c)return!0;if(ssc_isNodeName(s,"button")&&e.keyCode===ssc_key.spacebar)return!0;var t,r=0,o=0,n=ssc_overflowingAncestor(ssc_activeElement),a=n.clientHeight;switch(n==document.body&&(a=window.innerHeight),e.keyCode){case ssc_key.up:o=-ssc_arrowscroll;break;case ssc_key.down:o=ssc_arrowscroll;break;case ssc_key.spacebar:t=e.shiftKey?1:-1,o=-t*a*.9;break;case ssc_key.pageup:o=.9*-a;break;case ssc_key.pagedown:o=.9*a;break;case ssc_key.home:o=-n.scrollTop;break;case ssc_key.end:var i=n.scrollHeight-n.scrollTop-a;o=i>0?i+10:0;break;case ssc_key.left:r=-ssc_arrowscroll;break;case ssc_key.right:r=ssc_arrowscroll;break;default:return!0}ssc_scrollArray(n,r,o),e.preventDefault()}function ssc_mousedown(e){ssc_activeElement=e.target}function ssc_setCache(e,s){for(var c=e.length;c--;)ssc_cache[ssc_uniqueID(e[c])]=s;return s}function ssc_overflowingAncestor(e){var s=[],c=ssc_root.scrollHeight;do{var t=ssc_cache[ssc_uniqueID(e)];if(t)return ssc_setCache(s,t);if(s.push(e),c===e.scrollHeight){if(!ssc_frame||ssc_root.clientHeight+10<c)return ssc_setCache(s,document.body)}else if(e.clientHeight+10<e.scrollHeight&&(overflow=getComputedStyle(e,"").getPropertyValue("overflow"),"scroll"===overflow||"auto"===overflow))return ssc_setCache(s,e)}while(e=e.parentNode)}function ssc_addEvent(e,s,c){window.addEventListener(e,s,c||!1)}function ssc_removeEvent(e,s,c){window.removeEventListener(e,s,c||!1)}function ssc_isNodeName(e,s){return e.nodeName.toLowerCase()===s.toLowerCase()}function ssc_directionCheck(e,s){e=e>0?1:-1,s=s>0?1:-1,(ssc_direction.x!==e||ssc_direction.y!==s)&&(ssc_direction.x=e,ssc_direction.y=s,ssc_que=[])}function ssc_pulse_(e){var s,c,t;return e*=ssc_pulseScale,1>e?s=e-(1-Math.exp(-e)):(c=Math.exp(-1),e-=1,t=1-Math.exp(-e),s=c+t*(1-c)),s*ssc_pulseNormalize}function ssc_pulse(e){return e>=1?1:0>=e?0:(1==ssc_pulseNormalize&&(ssc_pulseNormalize/=ssc_pulse_(1)),ssc_pulse_(e))}var ssc_framerate=150,ssc_animtime=500,ssc_stepsize=150,ssc_pulseAlgorithm=!0,ssc_pulseScale=6,ssc_pulseNormalize=1,ssc_keyboardsupport=!0,ssc_arrowscroll=50,ssc_frame=!1,ssc_direction={x:0,y:0},ssc_initdone=!1,ssc_fixedback=!0,ssc_root=document.documentElement,ssc_activeElement,ssc_key={left:37,up:38,right:39,down:40,spacebar:32,pageup:33,pagedown:34,end:35,home:36},ssc_que=[],ssc_pending=!1,ssc_cache={};setInterval(function(){ssc_cache={}},1e4);var ssc_uniqueID=function(){var e=0;return function(s){return s.ssc_uniqueID||(s.ssc_uniqueID=e++)}}(),ischrome=/chrome/.test(navigator.userAgent.toLowerCase());ischrome&&(ssc_addEvent("mousedown",ssc_mousedown),ssc_addEvent("mousewheel",ssc_wheel),ssc_addEvent("load",ssc_init));

	</script>
</body>
</html>