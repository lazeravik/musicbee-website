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
require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';

include $siteRoot.'classes/Addon.php';
$addon = new addon(); //create an instance of the addondashboard class
$addonInfo = $addon->getAddonListByMember($_SESSION['memberinfo']['memberid'],100);
//Get all the info about the user at the begining
$info = $memberData->memberInfo($_SESSION['memberinfo']['memberid']); //get info about the user
$unapprovedAddonInfo = $addon->getAddonListByStatusAndMember($_SESSION['memberinfo']['memberid'],100,0);
?>


<div class="main_panel_width">
	<div class="wide_bar_wrap">
		<div class="wide_bar">
			<div class="infocard_header blue_color">
				<h3><?php echo $lang['127']; ?></h3>
				<p><?php echo $lang['129']; ?></p>
			</div>
			<ul>
				<li class="green_bottombar">
					<h1>
						<?php echo count($addonInfo); ?>
					</h1>
					<p>
						<?php echo $lang['130']; ?>
					</p>
				</li>
				<li class="yellow_bottombar">
					<h1>
						<?php echo count($unapprovedAddonInfo); ?>
					</h1>
					<p>
						<?php echo $lang['132']; ?>
					</p>
				</li>
				<li class="blue_bottombar">
					<h1>
						<?php echo $info['total_likeReceived']; ?>
					</h1>
					<p>
						<?php echo $lang['131']; ?>
					</p>
				</li>
				<div id="clear"></div>
			</ul>
		</div>
	</div>
	<div class="content_inner_wrapper_admin width100">
		<div class="admin_margin_wrapper">
			<div class="infocard_header teal_color">
				<h3><?php echo $lang['133']; ?></h3>
				<p><?php echo $lang['134']; ?></p>
			</div>
			<div class="info_table_wrap">
				<?php if($info['total_addon_submitted'] > 0): ?>

				<?php else: ?>
					<p class="show_info">
						<?php echo $lang['128']; ?>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="content_inner_wrapper_admin width100 addon_list">
		<div class="admin_margin_wrapper">
			<div class="infocard_header dark_grey">
				<h3><?php echo $lang['135']; ?></h3>
				<p><?php echo $lang['136']; ?></p>
			</div>
			<div class="info_table_wrap">
				<?php if($info['total_addon_submitted'] > 0): ?>
				<?php else: ?>
					<p class="show_info">
						<?php echo $lang['128']; ?>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div id="clear"></div>
</div>
<div class="sub_panel_width">
	<div class="wide_bar_wrap">
		<div class="wide_bar_vertical">
			<div class="infocard_header green_color">
				<h3>
					<?php echo $lang['137']; ?>
				</h3>
				<p><?php echo $lang['138']; ?></p>
			</div>
			<ul id="dashboard_sidebar_link">
				<li>
					<a href="">
						<?php echo $lang['139']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['140']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['141']; ?>
					</a>
				</li>
				<li>
					<hr class="line" />
				</li>
				<li>
					<a href="#submit" data-href="submit" data-load-page="dashboard.submit">
						<?php echo $lang['142']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['143']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['144']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['145']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['146']; ?>
					</a>
				</li>
				<div id="clear"></div>
			</ul>
		</div>
	</div>
</div>
<div id="clear"></div>
<script type="text/javascript">
//sidebar link redirect and ajax load
	$('ul#dashboard_sidebar_link > li > a[data-load-page]').on('click', function(e) {
		e.preventDefault();
		/* Act on the event */
		loadPageGet(generateUrl($(this).attr('data-load-page')), (!! $(this).attr('data-get-req'))? $(this).attr('data-get-req'): "");
		window.location.hash = $(this).attr('data-href');
	});
</script>