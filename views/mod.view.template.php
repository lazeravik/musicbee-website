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

$json_response = true;
$no_guests = true; //kick off the guests
$mod_only = true;
require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';

include $link['root'].'classes/Dashboard.php';
include $link['root'].'classes/Search.php';
$search = new Search();
$dashboard = new Dashboard();

$stat['total_download'] = $dashboard->getAddonDownloadCount(null);
$stat['total_likes'] = $dashboard->getAddonLikeCount(null);
$stat['total_addon'] = $dashboard->getAllAddonCount();
$stat['total_unapproved_addon'] = $dashboard->getAllAddonCountByStatus(0);
$stat['total_rejected_addon'] = $dashboard->getAllAddonCountByStatus(2);
$stat['total_softdeleted_addon'] = $dashboard->getAllAddonCountByStatus(3);

$stat['total_members'] = $dashboard->getAllMemberCount();
$stat['total_addon_publisher'] = $dashboard->getAllAddonPublisherCount();







//var_dump($stat['total_addon_publisher']);




if(isset($_GET['page_num'])) {
	if(is_int($_GET['page_num']) || ctype_digit($_GET['page_num'])) {
		$offset = ($_GET['page_num'] - 1) * $mb['view_range']['dashboard_all_view_range'];
		$current_page = $_GET['page_num'];
	} else {
		$offset = 0;
		$current_page = 1;
	}
} else {
	$offset = 0;
	$current_page = 1;
}
/**
 * Get all addon list submitted by this user
 * @var array $addondata
 */
if(isset($_GET['action'])) {
	if($_GET['action'] == "search" && (isset($_GET['query']) && isset($_GET['type']))) {
		$type = ($_GET['type'] == "all") ? null : $_GET['type'];
		$status = ($_GET['status'] == "all") ? "0,1,2,3" : $_GET['status'];
		$resultdata = $search->searchAddons($_GET['query'], $type, $status, $mb['user']['id'], $offset, $mb['view_range']['dashboard_all_view_range']);
	}
} else {
	$resultdata = $search->searchAddons(null, null, "0,1,2,3", $mb['user']['id'], $offset, $mb['view_range']['dashboard_all_view_range']);
}

/**
 * Calculate the total page required if it shows x number of items per page
 * @var int $page_total
 */
$page_total = ceil($resultdata['row_count'] / $mb['view_range']['dashboard_all_view_range']);


function dashboard_result_pagination_generator($page_total, $current_pagenum) {
	if($page_total > 0) {
		$pagination_view = '<ul class="pagination">';
		for($i = 1; $i < $page_total + 1; $i++) {
			if($current_pagenum == $i) {
				$pagination_view .= '<li><button class="btn btn_blue active" onclick="loadAddonPage(event,'.$i.')">'.$i.'</button></li>';
			} else {
				$pagination_view .= '<li><button class="btn btn_blue" onclick="loadAddonPage(event,'.$i.')">'.$i.'</button></li>';
			}
		}
		$pagination_view .= '</ul>';
	} else {
		$pagination_view = "";
	}

	return $pagination_view;
}

?>
<div class="main_content_wrapper col_2">
	<div class="sub_content_wrapper">
		<div class="box_content">
			<span class="show_info info_silver custom">
				<h3>All add-on statistic</h3>
			</span>
			<hr class="line"/>
			<table class="record">
				<tbody>
				<tr>
					<td>
						<a href="#mod_all" data-href="mod_all"><?php echo $lang['mod_4']; ?></a>
					</td>
					<td>
						<?php echo Format::number_format_suffix($stat['total_addon']); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mod_2']; ?>
					</td>
					<td>
						<?php echo Format::number_format_suffix($stat['total_likes']); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mod_1']; ?>
					</td>
					<td>
						<?php echo Format::number_format_suffix($stat['total_download']); ?>
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
						<?php echo Format::number_format_suffix($stat['total_unapproved_addon']); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mod_5']; ?>
					</td>
					<td>
						<?php echo Format::number_format_suffix($stat['total_rejected_addon']); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mod_6']; ?>
					</td>
					<td>
						<?php echo Format::number_format_suffix($stat['total_softdeleted_addon']); ?>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="sub_content_wrapper">
		<div class="box_content">
			<span class="show_info info_silver custom">
				<h3>Add-on publishers & users</h3>
			</span>
			<hr class="line"/>
			<table class="record">
				<tbody>
				<tr>
					<td>
						Total registered User
					</td>
					<td title="<?php echo $stat['total_members']; ?>">
						<?php echo Format::number_format_suffix($stat['total_members']); ?>
					</td>
				</tr>
				<tr>
					<td>
						Total add-on publishers
					</td>
					<td>
						<?php echo Format::number_format_suffix($stat['total_addon_publisher']); ?>
					</td>
				</tr>
				</tbody>
			</table>
<!--			<hr class="line"/>-->
<!--			<span class="show_info info_silver custom">-->
<!--				<h3>Actions</h3>-->
<!--			</span>-->
<!--			<hr class="line"/>-->
<!--			<ul class="link_list">-->
<!--				<li>-->
<!--					<a href="#mod_user" data-href="mod_user">-->
<!--						User Permission and Rank-->
<!--					</a>-->
<!--				</li>-->
<!--				<li>-->
<!--					<a href="">-->
<!--						Delete all add-ons by User-->
<!--					</a>-->
<!--				</li>-->
<!--			</ul>-->
		</div>
		<div class="box_content">
						<span class="show_info info_silver custom">
				<h3>Actions</h3>
			</span>
			<hr class="line"/>
			<ul class="link_list">
<!--				<li>-->
<!--					<a href="#mod_all" data-href="mod_all">-->
<!--						User Permission and Rank-->
<!--					</a>-->
<!--				</li>-->
				<li>
					<a href="#mod_all/action=search&status=3" data-href="mod_all/action=search&status=3">
						Undelete add-ons
					</a>
				</li>
				<li>
					<a href="#mod_all/action=search&status=0" data-href="mod_all/action=search&status=0">
						All unapproved add-ons
					</a>
				</li>
				<li>
					<a href="#mod_all/action=search&status=2" data-href="mod_all/action=search&status=2">
						All rejected add-ons
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>


<div class="space medium"></div>