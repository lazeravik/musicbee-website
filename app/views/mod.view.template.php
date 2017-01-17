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

$json_response = true;
$no_guests = true; //kick off the guests
$mod_only = true;
require_once $_SERVER['DOCUMENT_ROOT'].'/app/functions.php';

include $link['root'].'classes/Dashboard.php';
$dashboard = new Dashboard();

$stat['total_download'] = $dashboard->getAddonDownloadCount(null);
$stat['total_likes'] = $dashboard->getAddonLikeCount(null);
$stat['total_addon'] = $dashboard->getAllAddonCount();
$stat['total_unapproved_addon'] = $dashboard->getAllAddonCountByStatus(0);
$stat['total_rejected_addon'] = $dashboard->getAllAddonCountByStatus(2);
$stat['total_softdeleted_addon'] = $dashboard->getAllAddonCountByStatus(3);

$stat['total_members'] = $dashboard->getAllMemberCount();
$stat['total_addon_publisher'] = $dashboard->getAllAddonPublisherCount();


?>
<div class="main_content_wrapper col_2">
	<div class="sub_content_wrapper">
		<div class="box_content">
			<div class="show_info info_silver custom">
				<h3><?php echo $lang['mod_11']; ?></h3>
			</div>
			<hr class="line"/>
			<ul class="link_list">
				<li>
					<a href="#mod_all/action=search&status=3" data-href="mod_all/action=search&status=3">
						<?php echo $lang['mod_12']; ?>
					</a>
				</li>
				<li>
					<a href="#mod_all/action=search&status=0" data-href="mod_all/action=search&status=0">
						<?php echo $lang['mod_13']; ?>
					</a>
				</li>
				<li>
					<a href="#mod_all/action=search&status=2" data-href="mod_all/action=search&status=2">
						<?php echo $lang['mod_14']; ?>
					</a>
				</li>
				<li>
					<a href="#mod_transfer" data-href="mod_transfer">
						<?php echo $lang['transfer_ownership_btn']; ?>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="sub_content_wrapper">
		<div class="box_content">
			<div class="show_info info_silver custom">
				<h3><?php echo $lang['mod_8']; ?></h3>
			</div>
			<hr class="line"/>
			<table class="record">
				<tbody>
				<tr>
					<td>
						<?php echo $lang['mod_9']; ?>
					</td>
					<td title="<?php echo $stat['total_members']; ?>">
						<?php echo Format::numberFormatSuffix($stat['total_members']); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mod_10']; ?>
					</td>
					<td>
						<?php echo Format::numberFormatSuffix($stat['total_addon_publisher']); ?>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<div class="box_content">
			<div class="show_info info_silver custom">
				<h3><?php echo $lang['mod_7']; ?></h3>
			</div>
			<hr class="line"/>
			<table class="record">
				<tbody>
				<tr>
					<td>
						<a href="#mod_all" data-href="mod_all"><?php echo $lang['mod_4']; ?></a>
					</td>
					<td>
						<?php echo Format::numberFormatSuffix($stat['total_addon']); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mod_2']; ?>
					</td>
					<td>
						<?php echo Format::numberFormatSuffix($stat['total_likes']); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mod_1']; ?>
					</td>
					<td>
						<?php echo Format::numberFormatSuffix($stat['total_download']); ?>
					</td>
				</tr>
				</tbody>
			</table>

			<hr class="line"/>
			<table class="record">
				<tbody>
				<tr>
					<td>
						<?php echo $lang['mod_3']; ?>
					</td>
					<td>
						<?php echo Format::numberFormatSuffix($stat['total_unapproved_addon']); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mod_5']; ?>
					</td>
					<td>
						<?php echo Format::numberFormatSuffix($stat['total_rejected_addon']); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mod_6']; ?>
					</td>
					<td>
						<?php echo Format::numberFormatSuffix($stat['total_softdeleted_addon']); ?>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>


<div class="space medium"></div>
