<?php
$no_guests = true; //kick off the guests
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';

include $siteRoot . 'classes/Dashboard.php';
$dashboard = new Dashboard(); //create an instance of the addondashboard class

//check if the user has a rank or not, if not, then there is no addon account created for the user
if ($dashboard->memberRank($user_info['id']) == null) {
    $permission = 10;
    if ($user_info['is_admin']) {
        $permission = 1; //permission level 1 means the member is admin
    } elseif ($user_info['is_mod']) {
        $permission = 2; //permission level 2 means the member is mod
    }
    //Create an Addon dashboard account for the user
    $dashboard->createAddonAccount($user_info['id'], $permission, $user_info['name']);
    //header('Location: '.$_SERVER['PHP_SELF']); //reload the page to set the session properly
} else {
    //If the memebr has an account but the permission session is not set, this is the time to do so...
    $_SESSION['dashboard_permission'] = $dashboard->memberRank($user_info['id']);
}


//Addon Account Related Session Initialize
$getmemberinfo = $dashboard->memberInfo($user_info['id']); //Gets all member info from the DB

/* We will use an arra to store all the member data. the data contains:
name, id, rank(rank name), raw rank(rank id)                    */
$memberinfoArray['membername'] = $getmemberinfo[0]['membername'];
$memberinfoArray['memberid'] = $getmemberinfo[0]['ID_MEMBER'];
$memberinfoArray['rank'] = $dashboard->rankName($getmemberinfo[0]['rank']);
$memberinfoArray['rank_raw'] = $getmemberinfo[0]['rank'];

/*store memberinfoArray in the session. Now we can get the value anytime
eg. $_SESSION['memberinfo']['rank'] or $_SESSION['memberinfo']['rawrank']   */
$_SESSION['memberinfo'] = $memberinfoArray;
//var_dump($_SESSION['memberinfo'] );

?>
<!DOCTYPE html>
<html>
<head>
    <title>MusicBee Addon Dashboard</title>
    <!--include common meta tags and stylesheets -->
    <?php include('./includes/meta&styles.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>/styles/MusicBeeAdminPanel.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>/styles/MusicBeeAddons.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>/styles/animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>/styles/markdownView.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>/styles/wmd.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>/styles/code-style/obsidian.css">
    <!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
    <?php include('./includes/font.helper.php'); ?>
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
        <div class="top_infobar addon_dashboard_color ">
            <div class="infobar_wrapper">
                <h2>
                    <?php echo $lang['147']; ?>
                </h2>
                <p><?php echo $lang['148']; ?></p>
            </div>
            <!-- AddOn page navigation top menu -->
            <div class="secondery_nav" id="secondery_nav">
                <div id="nav" class="secondery_nav_wrap">
                    <ul class="left">
                        <li>
                            <a href="#overview" data-href="overview" data-load-page="dashboard.view"><?php echo $lang['225']; ?></a>
                        </li>
                        <li>
                            <a href="#all" data-href="all" data-load-page="dashboard.all"><?php echo $lang['226']; ?>
                            </a>
                        </li>
                        <li>
                            <a class="btn btn_green addon_panel_btn" href="#submit" data-href="submit" data-load-page="dashboard.submit"><?php echo $lang['227']; ?>
                            </a>
                        </li>
                        <li>
                            <div id="loading_icon" class="spinner fadeIn animated" style="display:none;">
                                <div class="double-bounce1"></div>
                                <div class="double-bounce2"></div>
                            </div>
                        </li>
                        <div id="clear"></div>
                    </ul>
                    <ul class="right">
                        <li>
                            <a class="btn addon_panel_btn" href="#" title="Your rank is <?php echo $_SESSION['memberinfo']['rank']; ?>">
                                <i class="fa fa-shield"></i>&nbsp;&nbsp; <?php echo $_SESSION['memberinfo']['rank']; ?>
                            </a>
                        </li>
                        <div id="clear"></div>
                    </ul>
                    <div id="clear"></div>
                </div>
            </div>
        </div>
        <div id="main">
            <div id="main_panel">
                <div class="content_wrapper_admin" id="ajax_area">

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

<script src="<?php echo $siteUrl; ?>/scripts/jquery-2.1.4.min.js"></script>
<script src="<?php echo $siteUrl; ?>/scripts/jquery.tagsinput.min.js"></script>
<!-- remove the following script for making the header non-sticky -->
<script src="<?php echo $siteUrl; ?>/scripts/jquery.sticky.min.js"></script>
<script src="<?php echo $siteUrl; ?>/scripts/markdown-it.min.js"></script>
<script src="<?php echo $siteUrl; ?>/scripts/highlight/highlight.pack.js"></script>
<script src="<?php echo $siteUrl; ?>/scripts/wmdMod.js"></script>
<script src="<?php echo $siteUrl; ?>/scripts/modalBox.js"></script>

<?php
include_once $siteRoot . 'includes/ajax.navigation.script.php';
?>
<script>
	function notificationCallback(data) {
	var obj = jQuery.parseJSON(data);
	if (obj.status == 0) {
		showNotification(obj.data, "error", "red_color");
	} else if (obj.status == 1) {
		showNotification(obj.data, "success", "green_color");
	}
}
</script>
</body>
</html>