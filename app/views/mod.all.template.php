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
include $link['root'].'classes/Search.php';
$search = new Search();
$dashboard = new Dashboard();


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
	if($_GET['action'] == "search") {

		if(isset($_GET['query']))
			$query = $_GET['query'];
		else
			$query = null;

		if(isset($_GET['type']))
			$type = ($_GET['type'] == "all") ? null : $_GET['type'];
		else
			$type = null;

		if(isset($_GET['status']))
			$status = ($_GET['status'] == "all") ? "0,1,2,3" : $_GET['status'];
		else
			$status = "0,1,2,3";

		$resultdata = $search->searchAddons($query, $type, $status, null, $offset, $mb['view_range']['dashboard_all_view_range']);
	}
} else {
		$resultdata = $search->searchAddons(null, null, "0,1,2,3", null, $offset, $mb['view_range']['dashboard_all_view_range']);
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
<div class="main_content_wrapper col_2_1">
	<div class="sub_content_wrapper" id="addon_records">
		<div class="box_content">
				<span class="show_info custom warning">
					<h3><?php echo $lang['mod_4']; ?></h3>
				</span>
			<?php if(!empty($resultdata['result'])): ?>

				<table class="record">
					<thead>
					<tr>
						<td>
							<?php echo $lang['dashboard_record_th_1']; ?>
						</td>
						<td>
							<?php echo $lang['dashboard_record_th_7']; ?>
						</td>
						<td>
							<?php echo $lang['dashboard_record_th_2']; ?>
						</td>
						<td>
							<?php echo $lang['dashboard_record_th_4']; ?>
						</td>
						<td>

						</td>
					</tr>
					</thead>
					<tbody>
					<?php foreach($resultdata['result'] as $key => $addon): ?>
						<tr id="<?php echo $addon['ID_ADDON']; ?>_record" class="<?php echo ($addon['status'] == "3") ? "deleted" : ""; ?>">
							<td>
								<a href="<?php echo $link['addon']['home'].$addon['ID_ADDON']."/".Format::slug($addon['addon_title']); ?>"
								   target="_blank"
								   title="View this addon"><?php echo $addon['addon_title']; ?><?php if($addon['is_beta'] == 1): ?>&nbsp;
										<p class="small_info beta"><?php echo $lang['addon_38']; ?></p><?php endif; ?>
								</a>
							</td>
							<td>
								<?php echo $addon['membername']; ?>
							</td>
							<td>
								<?php echo $mb['main_menu']['add-ons']['sub_menu'][$addon['category']]['title']; ?>
							</td>
							<td class="status">
								<?php $status_array_each = Validation::getStatus($addon['status']); ?>
								<p class="small_info <?php echo Format::slug($status_array_each['text']); ?>">
									<?php echo $status_array_each['icon'].' '.$status_array_each['text']; ?>
								</p>

							</td>

							<?php
							$button_stat_text = ($addon['status'] == "3") ? "disabled" : "";
							?>

							<td class="action_input">
								<form id="<?php echo $addon['ID_ADDON']; ?>"
								      action="<?php echo $link['app-url']; ?>includes/dashboard.tasks.php"
								      method="post"
								      data-autosubmit>
									<button
											id="<?php echo $addon['ID_ADDON']; ?>"
											class="btn btn_red"
											title="<?php echo $lang['dashboard_tooltip_1']; ?>"
											onclick="deleteRecord(<?php echo $addon['ID_ADDON']; ?>);"
											<?php echo $button_stat_text; ?>>
										<i class="fa fa-trash"></i>
									</button>
									<input
											type="hidden"
											name="record_id"
											value="<?php echo $addon['ID_ADDON']; ?>"/>
									<input
											type="hidden"
											name="modify_type"
											value="soft_delete"/>
								</form>
								<button
										class="btn btn_blue"
										type="submit"
										title="<?php echo $lang['dashboard_tooltip_2']; ?>"
										onclick="loadEditView(<?php echo $addon['ID_ADDON']; ?>);">
									<?php echo $lang['edit_icon']; ?></button>

								<?php if($addon['status'] == 0 || $addon['status'] == 1 ): ?>
								<form id="addon_reject"
										action="<?php echo $link['app-url']; ?>includes/dashboard.tasks.php"
										method="post"
										data-autosubmit>
									<button
											class="btn btn_yellow"
											type="submit"
											onclick="addonReject(<?php echo $addon['ID_ADDON']; ?>)">
										<?php echo $lang['reject']; ?>
									</button>
									<input
											type="hidden"
											name="addon_id"
											value="<?php echo $addon['ID_ADDON']; ?>"/>
									<input
											type="hidden"
											name="addon_approve"
											value="2"/>
								</form>
						<?php
						endif;
						if($addon['status'] != 1): ?>
								<form
										id="addon_approve"
										action="<?php echo $link['app-url']; ?>includes/dashboard.tasks.php"
										method="post"
										data-autosubmit>
									<button
											class="btn btn_blue"
											type="submit"
											onclick="addonApprove(<?php echo $addon['ID_ADDON']; ?>)">
									<?php if($addon['status'] == 3){ ?>
										<?php echo $lang['undelete']; ?>
									<?php }else{ ?>
										<?php echo $lang['approve']; ?>
									<?php } ?>
									</button>
									<input
											type="hidden"
											name="addon_id"
											value="<?php echo $addon['ID_ADDON']; ?>"/>
									<input
											type="hidden"
											name="addon_approve"
											value="1"/>
								</form>
								<?php endif; ?>


							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>

				</table>
			<?php else: ?>
				<p class="message"><?php echo $lang['dashboard_err_2']; ?></p>
			<?php endif; ?>
		</div>
		<div class="box_content">
			<?php echo dashboard_result_pagination_generator($page_total, $current_page); ?>
		</div>
	</div>

	<div class="sub_content_wrapper">
		<div class="box_content">
			<form id="search_filter" action="<?php echo $link['url']; ?>views/mod.all.template.php" method="get" data-autosubmit>
			<span class="show_info info_silverwhite custom">
				<input type="search" spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off" class="search filter_search dark" name="query" placeholder="<?php echo $lang['search_submitted_addons']; ?>" onkeydown="searchEnter(event)" value="<?php echo (isset($_GET['query']))?$_GET['query']: ''; ?>">
				<input type="hidden" name="action" value="search">
			</span>
				<ul class="form">
					<li>
						<label for="type">
							<p><?php echo $lang['addon_11']; ?></p>
						</label>
						<select name="type" id="type" onchange="searchDropdown(event)">
							<?php
							if(!isset($_GET['type'])){
								$addon_type = 'all';
							} elseif(isset($_GET['type']) && $type){
								if($_GET['type']=='all'){
									$addon_type = 'all';
								} else {
									$addon_type = $_GET['type'];
								}
							} else {
								$addon_type = isset($_GET['type'])?$_GET['type']:'all';
							}
							?>
							<option value="all" selected>All</option>
							<?php
							foreach($mb['main_menu']['add-ons']['sub_menu'] as $key => $menu_addon) {
								echo '<option value="'.$menu_addon['id'].'" ',($addon_type == $menu_addon['id'])?'selected':'',' >'.$menu_addon['title'].'</option>';
							}
							?>
						</select>
					</li>
				</ul>
				<ul class="form">
					<li>
						<label for="status">
							<p><?php echo $lang['dashboard_record_th_4']; ?></p>
						</label>
						<select name="status" id="status" onchange="searchDropdown(event)">
							<?php
							if(!isset($_GET['status'])){
								$addon_stat = 'all';
							} elseif(isset($_GET['status']) && $status){
								if($_GET['status']=='all'){
									$addon_stat = 'all';
								} else {
									$addon_stat = $_GET['status'];
								}
							} else {
								$addon_stat = isset($_GET['status'])?$_GET['status']:'all';
							}
							?>

							<option value="all" <?php echo ($addon_stat=='all')? 'selected' :''; ?>>All</option>
							<option value="0"   <?php echo ($addon_stat=='0')    ? 'selected' :''; ?>><?php echo $lang['addon_status_1']; ?></option>
							<option value="1"   <?php echo ($addon_stat=='1')    ? 'selected' :''; ?>><?php echo $lang['addon_status_2']; ?></option>
							<option value="2"   <?php echo ($addon_stat=='2')    ? 'selected' :''; ?>><?php echo $lang['addon_status_3']; ?></option>
							<option value="3"   <?php echo ($addon_stat=='3')    ? 'selected' :''; ?>><?php echo $lang['addon_status_4']; ?></option>
						</select>
					</li>
				</ul>
			</form>
			<input type="hidden" id="page_num" name="page_num" value="<?php echo isset($_GET['page_num'])?$_GET['page_num'] : '1'; ?>"/>
		</div>
	</div>
</div>

<div class="space medium"></div>

<script type="text/javascript">
	searchEnter = function (event) {
		if (event.keyCode == 13) {
			$('#page_num').val(1);
			loadAddonPage(event, $('#page_num').val());
		}
	}

	searchDropdown = function (event) {
		$('#page_num').val(1);
		loadAddonPage(event, $('#page_num').val());
	}

	//get page(1,2,3..) addon list via ajax
	loadAddonPage = function (event, page_num) {
		event.preventDefault();
		event.stopImmediatePropagation();
		var hashedUrl = (window.location.hash).replace('#', '').split('/');
		var $reqparam = $('form[data-autosubmit][id=search_filter]').serialize();
		var url =  hashedUrl[0]+'/'+$reqparam+'&page_num='+page_num;
		window.location.hash = url;
	}

	//Store to be deleted record id in a variable, later we can use this to locate the table row and remove it.
	var record_id;

	function addonApprove(id) {
		//store the record id so that we can remove the table from html
		record_id = id;
		$('form[data-autosubmit][id=addon_approve]').autosubmit();
	}

	function addonReject(id) {
		//store the record id so that we can remove the table from html
		record_id = id;
		$('form[data-autosubmit][id=addon_reject]').autosubmit();
	}

	function loadEditView(id) {
		$('#loading_icon').show(); //show loading icon'
		showOverlay(); //show overlay while loading
		window.location.hash = '/' + id +'/update/mod';
	}

	function deleteRecord(id) {
		var modify_confirm = confirm("<?php echo $lang['dashboard_msg_14']; ?>");
		if (modify_confirm == true) {
			hideNotification();
			$('#loading_icon').show();
			//store the record id so that we can remove the table from html
			record_id = id;
			$('form[data-autosubmit][id=' + id + ']').autosubmit();
		} else {
			this.event.preventDefault(); //stop the actual form submission
		}
	}

	var reload_addon_approval_list_overview = function () {
		var hashedUrl = (window.location.hash).replace('#', '').split('/');
		var $generatedUrl = generatePageUrl(hashedUrl[0]);
		var $reqParam =  '?'+hashedUrl[1];

		$('#loading_icon').show(); //show loading icon'
		$.ajax({
			url: $generatedUrl+$reqParam,
			cache: false,
			type: "POST",
		}).done(function (data) {
			var sourcedata = $('#addon_records > *', $(data));
			$('#addon_records').html(sourcedata).fadeIn();
		}).fail(function (jqXHR, textStatus, errorThrown) {
			showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "red_color");
		}).always(function () {
			$('#loading_icon').hide(); //show loading icon'
		});
	}

	//Anonymous callback function for removing table row
	var remove_addon_record = function () {
		reload_addon_approval_list_overview();
	}
</script>
