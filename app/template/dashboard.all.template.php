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

$no_guests = true; //kick off the guests
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/functions.php';

include $link['root'] . 'classes/Dashboard.php';
include $link['root'] . 'classes/Search.php';
$search = new Search();


if (isset($_GET['page_num'])){
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

		$resultdata = $search->searchAddons ($query, $type, $status, $mb['user']['id'],$offset,$mb['view_range']['dashboard_all_view_range']);
	}
} else {
	$resultdata = $search->searchAddons (null, null, "0,1,2,3", $mb['user']['id'],$offset,$mb['view_range']['dashboard_all_view_range']);
}

/**
 * Calculate the total page required if it shows x number of items per page
 * @var int $page_total
 */
$page_total = ceil ($resultdata['row_count'] / $mb['view_range']['dashboard_all_view_range']);



function dashboard_result_pagination_generator($page_total, $current_pagenum) {
	if ($page_total > 0) {
		$pagination_view = '<ul class="pagination">';
		for ($i = 1; $i < $page_total + 1; $i++) {
			if ($current_pagenum == $i) {
				$pagination_view .= '<li><button class="btn btn_blue active" onclick="loadAddonPage(event,' . $i . ')">' . $i . '</button></li>';
			} else {
				$pagination_view .= '<li><button class="btn btn_blue" onclick="loadAddonPage(event,' . $i . ')">' . $i . '</button></li>';
			}
		}
		$pagination_view .= '</ul>';
	} else {
		$pagination_view = "";
	}

	return $pagination_view;
}

?>
<div class="main_content_wrapper col_1_2">
	<div class="sub_content_wrapper">
		<div class="box_content">
			<span class="show_info info_darkgrey custom">
				<h3><?php echo $lang['dashboard_10']; ?></h3>
			</span>
			<form id="search_filter" action="<?php echo $link['app-url']; ?>views/dashboard.all.template.php" method="get" data-autosubmit>
			<span class="show_info info_silverwhite custom">
				<input type="search" spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off" class="search filter_search dark" name="query" placeholder="<?php echo $lang['search_your_submitted_addons']; ?>" onkeydown="searchEnter(event)" >
				<input type="hidden" name="action" value="search">
			</span>
				<ul class="form">
					<li>
						<label for="type">
							<p><?php echo $lang['addon_11']; ?></p>
						</label>
						<select name="type" id="type" onchange="searchDropdown(event)">
							<option value="all">All</option>
							<?php
							foreach ($mb['main_menu']['add-ons']['sub_menu'] as $key => $menu_addon) {
								echo "<option value=\"" . Format::slug ($menu_addon['id']) . "\">" . $menu_addon['title'] . "</option>";
							}
							?>
						</select>
					</li>
				</ul>
				<ul class="form">
					<li>
						<label for="status"><p><?php echo $lang['dashboard_record_th_4']; ?></p></label>
						<select name="status" id="status" onchange="searchDropdown(event)">
							<option value="all" <?php echo (!isset($_GET['status']))? 'selected' :''; ?>>All</option>
							<option value="0"  <?php echo (isset($_GET['status']) && $status==0)? 'selected' :''; ?>><?php echo $lang['addon_status_1']; ?></option>
							<option value="1"  <?php echo (isset($_GET['status']) && $status==1)? 'selected' :''; ?>><?php echo $lang['addon_status_2']; ?></option>
							<option value="2"  <?php echo (isset($_GET['status']) && $status==2)? 'selected' :''; ?>><?php echo $lang['addon_status_3']; ?></option>
							<option value="3"  <?php echo (isset($_GET['status']) && $status==3)? 'selected' :''; ?>><?php echo $lang['addon_status_4']; ?></option>
						</select>
					</li>
				</ul>
				<hr class="line">

				<input id="page_num" type="hidden" name="page_num" value="1">
			</form>
		</div>
	</div>
	<div class="sub_content_wrapper" id="addon_records">
		<div class="box_content">
				<span class="show_info custom">
					<h3><?php echo $lang['dashboard_11']; ?></h3>
				</span>
			<?php if (!empty($resultdata['result'])): ?>

				<table class="record">
					<thead>
					<tr>
						<td>
							<?php echo $lang['dashboard_record_th_1']; ?>
						</td>
						<td>
							<?php echo $lang['dashboard_record_th_2']; ?>
						</td>
						<td>
							<?php echo $lang['dashboard_3']; ?>
						</td>
						<td>
							<?php echo $lang['dashboard_record_th_4']; ?>
						</td>
						<td>

						</td>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($resultdata['result'] as $key => $addon): ?>
						<tr id="<?php echo $addon['ID_ADDON']; ?>_record" class="<?php echo ($addon['status'] == "3") ? "deleted" : ""; ?>">
							<td>
								<a href="<?php echo $link['addon']['home'] . $addon['ID_ADDON'] . "/" . Format::slug ($addon['addon_title']); ?>"
								   target="_blank"
								   title="View this addon"><?php echo $addon['addon_title']; ?><?php if ($addon['is_beta'] == 1): ?>&nbsp;
										<p class="small_info beta"><?php echo $lang['addon_38']; ?></p><?php endif; ?>
								</a>
							</td>
							<td>
								<?php echo Format::unslugTxt ($mb['main_menu']['add-ons']['sub_menu'][$addon['category']]['title']); ?>
							</td>
							<td>
								<?php echo Format::numberFormatSuffix ($addon['downloadCount']); ?>
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
										onclick="loadEditView(<?php echo $addon['ID_ADDON']; ?>);"
										<?php echo $button_stat_text; ?>><?php echo $lang['edit_icon']; ?></button>

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
			<?php echo dashboard_result_pagination_generator ($page_total, $current_page); ?>
		</div>
	</div>

</div>

<div class="space medium"></div>

<script type="text/javascript">
	searchEnter = function(event){
		if (event.keyCode == 13) {
			//reset pagination for search
			$('#page_num').val(1);
			searchFilterAddon(event);
		}
	}

	searchDropdown = function (event){
		//reset pagination for dropdown filter
		$('#page_num').val(1);
		searchFilterAddon(event);
	}

	searchFilterAddon = function(event) {
		$('#loading_icon').show(); //show loading icon'
		showOverlay(); //show overlay while loading
		var form = $('form[data-autosubmit][id=search_filter]');
		event.preventDefault();
		event.stopImmediatePropagation();
		$.ajax({
			type: form.attr('method'),
			url: form.attr('action'),
			data: form.serialize()
		}).done(function (data) {
			var sourcedata = $('#addon_records > *', $(data));
			$('#addon_records').html(sourcedata).fadeIn();
		}).fail(function (jqXHR, textStatus, errorThrown) {
			showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "red_color");
		}).always(function () {
			$('#loading_icon').hide(); //show loading icon'
			hideOverlay(); //show overlay while loading
		});
	}

	//get page(1,2,3..) addon list via ajax
	loadAddonPage = function (event,page_num) {
		$('#page_num').val(page_num);
		searchFilterAddon(event);
	}

	function loadEditView(id) {
		$('#loading_icon').show(); //show loading icon'
		showOverlay(); //show overlay while loading
		window.location.hash = '/'+id+'/update';
	}

	//Store to be deleted record id in a variable, later we can use this to locate the table row and remove it.
	var delete_record_id;
	function deleteRecord(id) {
		var modify_confirm = confirm("<?php echo $lang['dashboard_msg_2']; ?>");
		if (modify_confirm == true) {
			hideNotification();
			$('#loading_icon').show();
			//store the record id so that we can remove the table from html
			delete_record_id = id;
			$('form[data-autosubmit][id=' + id + ']').autosubmit();
		} else {
			this.event.preventDefault(); //stop the actual form submission
		}
	}


	//Anonymous callback function for removing table row
	var remove_addon_record = function () {
		$('#' + delete_record_id + "_record").html("<td><p><?php echo $lang['dashboard_msg_1']; ?></p></td><td></td><td></td><td></td>");
		$('#' + delete_record_id + "_record").addClass('record_removed');
	}
</script>
