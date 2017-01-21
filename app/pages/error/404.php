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

include_once $_SERVER['DOCUMENT_ROOT'].'/app/functions.php';

$title          = $lang['404_page_not_found'];
$description    = "";
$keywords       = "";
$isFontHelperDisabled = false;

include_once $link['view-dir'] . 'header.template.php';
?>

<!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
<?php
include($mainmenu);
?>

<!-- BODY CONTENT -->
<div id="main">
	<div id="main_panel">

		<div class="mb_landing align_right">
			<div class="sub_content">
				<div class="hero_text_top">
					<h1><?php echo $lang['404']; ?></h1>
					<h2><?php echo $lang['you_lost']; ?></h2>
					<br/>
					<p><?php echo $lang['page_not_exist']; ?></p>
					<br/>
					<br/>
					<hr class="line"/>
					<a href="<?php echo $link['url']; ?>" class="btn btn_green"><?php echo $lang['go_to_home']; ?></a>
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
</body>
</html>
