<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';
?>
<!DOCTYPE html>
<html>
	<head>
	<title><?php echo $lang['197']; ?></title>
	<!--include common meta tags and stylesheets -->
	<?php include $siteRoot.'includes/meta&styles.php'; ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>styles/404.css">
	<!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
	<?php include $siteRoot.'includes/font.helper.php'; ?>
	</head>
	<body>
	<div id="indexBackground">
	<div id="wrapper">
	<!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
	<?php 
		include($mainmenu); 
	?>

	<!-- BODY CONTENT -->
	  <div id="main">
	    <div id="main_panel">
	    	<div class="content">
	    			<div class="sub_content">
	    				<h1><?php echo $lang['194']; ?></h1>
	    				<h2><?php echo $lang['195']; ?></h2>
	    				<h4>
	    					<?php echo $lang['196']; ?><a href="<?php echo $link['help']; ?>">
	    					<br/>
	    					<br/>
	    					<br/>
	    					<a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>" class="btn btn_green"><?php echo $lang['198']; ?></a>
	    				</h4>
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
	</body>
</html>