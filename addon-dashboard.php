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

$no_guests = true; //kick off the guests
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $lang['dashboard_title']; ?></title>
    <!--include common meta tags and stylesheets -->
    <?php include('./includes/meta&styles.php'); ?>

    <link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>styles/animate.css">
    <!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
    <?php include('./includes/font.helper.php'); ?>
</head>
<body>
    <!--IMPORTANT-->
    <!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
    <?php
    include($mainmenu);
    ?>
    <!-- BODY CONTENT -->
    <div class="top_infobar addon_dashboard_color ">
        <div class="infobar_wrapper">
            <div class="infobar_inner_wrapper">
                <h2>
                    <?php echo $lang['dashboard_infobar_title']; ?>
                </h2>
                <p><?php echo $lang['dashboard_infobar_desc']; ?></p>
            </div>
        </div>
        <!-- AddOn page navigation top menu -->
        <div class="secondery_nav" id="secondery_nav">
            <div id="nav" class="secondery_nav_wrap">
                <ul class="left">
                    <li class="expand"><a href="javascript:void(0)" onclick="expand_second_menu()"><i class="fa fa-bars"></i></a></li>
                    <li>
                        <a href="#dashboard_overview" data-href="dashboard_overview"><?php echo $lang['dashboard_menu_1']; ?></a>
                    </li>
                    <li>
                        <a href="#dashboard_all" data-href="dashboard_all"><?php echo $lang['dashboard_menu_2']; ?>
                        </a>
                    </li>
                    <li>
                        <a href="#dashboard_submit" data-href="dashboard_submit"><?php echo $lang['dashboard_menu_3']; ?>
                        </a>
                    </li>

                    <li>
                        <div id="loading_icon" class="spinner fadeIn animated" style="display:none;">
                            <div class="double-bounce1"></div>
                            <div class="double-bounce2"></div>
                        </div>
                    </li>
                </ul>
                <ul class="right">
                    <li>
                        <a class="btn addon_panel_btn" href="javascript:void(0)" title="Your rank is <?php echo $_SESSION['memberinfo']['rank']; ?>">
                            <i class="fa fa-shield"></i>&nbsp;&nbsp; <?php echo $_SESSION['memberinfo']['rank']; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="main">
        <div id="main_panel">
            <div class="content_wrapper_admin" id="ajax_area">

            </div>
        </div>
    </div>
    <!--IMPORTANT-->
    <!-- INCLUDE THE FOOTER AT THE END -->
    <?php
    include($footer);
    ?>

    <script src="<?php echo $siteUrl; ?>scripts/jquery-2.1.4.min.js"></script>
    <script src="<?php echo $siteUrl; ?>scripts/jquery.sticky.min.js"></script>
    <script src="<?php echo $siteUrl; ?>scripts/markdown-it.min.js"></script>
    <script src="<?php echo $siteUrl; ?>scripts/highlight/highlight.pack.js"></script>
    <script src="<?php echo $siteUrl; ?>scripts/markdownEditor.js"></script>
    <script src="<?php echo $siteUrl; ?>scripts/modalBox.js"></script>
    <script src="<?php echo $siteUrl; ?>scripts/menu.navigation.js"></script>

    <script>
        var defaultpage = "dashboard_overview";
    </script>

    <?php
    include_once $siteRoot . 'includes/ajax.navigation.script.php';
    ?>

</body>
</html>