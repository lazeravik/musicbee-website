<?php 
$admin_only = true; //only for admins
require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $lang['AP_TITLE']; ?></title>
<!--include common meta tags and stylesheets -->
<?php include ('./includes/meta&styles.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>/styles/MusicBeeAdminPanel.css">
<link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>/styles/animate.css">
<link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>/styles/markdownView.css">
<link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>/styles/wmd.css">
<link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>/styles/code-style/obsidian.css">

<!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
<?php include ('./includes/font.helper.php'); ?>

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
		<div class="top_infobar admin_panel_color">
			<div class="infobar_wrapper">
				<h2>
					<?php echo $lang['AP_HEADER_TITLE']; ?>
				</h2>
				<p><?php echo $lang['AP_HEADER_DESC']; ?></p>
			</div>
			<div class="secondery_nav" id="secondery_nav">
			<div id="nav" class="secondery_nav_wrap">
				<ul class="left">
					<li>
					<a href="#overview" data-href="overview" data-load-page="adminpanel.view"><i class="fa fa-bar-chart"></i>&nbsp;&nbsp; Overview</a></li>
					<li>
					<a href="#viewAll" data-href="viewAll" data-load-page="adminpanel.view" data-get-req='view=all'>
					<i class="fa fa-object-ungroup"></i>&nbsp;&nbsp; All MusicBee Releases</a>
					</li>
					<li>
						<div id="loading_icon" class="spinner fadeIn animated" style="display:none;">
						  <div class="double-bounce1"></div>
						  <div class="double-bounce2"></div>
						</div>
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
	<!-- IMPORTANT! ***** DO NOT REMOVE IT!
	this is for fake ajax request data. This is needed for back and forward navigation as well as
	if someone uses link like "admin-panel.php#edit" the hash will load that page -->
	<a href="#edit" data-href="edit" data-load-page="adminpanel.edit" data-get-req="view=edit"></a>
	<a href="#editbeta" data-href="editbeta" data-load-page="adminpanel.edit" data-get-req="view=editbeta"></a>
	<!--IMPORTANT-->
	<!-- INCLUDE THE FOOTER AT THE END -->
	<?php 
		include($footer); 
	?>
<script src="<?php echo $siteUrl; ?>/scripts/markdown-it.min.js"></script>
<script src="<?php echo $siteUrl; ?>/scripts/highlight/highlight.pack.js"></script>	
<script src="<?php echo $siteUrl; ?>/scripts/jquery-2.1.4.min.js"></script>	
<script src="<?php echo $siteUrl; ?>/scripts/jquery.sticky.min.js"></script>
<script src="<?php echo $siteUrl; ?>/scripts/modalBox.js"></script>
<?php 
	include_once $siteRoot.'includes/ajax.navigation.script.php';
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