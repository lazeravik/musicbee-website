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

$admin_only = true; //only for admins
include_once $_SERVER['DOCUMENT_ROOT'].'/app/functions.php';
require_once $link['root'].'includes/admin.tasks.php';

if(isset($_GET['page_num'])) {
	if(is_int($_GET['page_num']) || ctype_digit($_GET['page_num'])) {
		$offset = ($_GET['page_num'] - 1) * $mb['view_range']['release_all_view_range'];
		$current_page = $_GET['page_num'];
	} else {
		$offset = 0;
		$current_page = 1;
	}
} else {
	$offset = 0;
	$current_page = 1;
}

$currentVersion = getVersionInfo(0, "byCurrentVersion"); //Get the current stable release info
$allRecords = getAllVersion($offset, $mb['view_range']['release_all_view_range']); // Get all MusicBee release versions till now
$totalRecord = getAllVersionCount();


/**
 * Calculate the total page required if it shows x number of items per page
 * @var int $page_total
 */
$page_total = ceil($totalRecord / $mb['view_range']['release_all_view_range']);

function release_result_pagination_generator($page_total, $current_pagenum) {
	if($page_total > 0) {
		$pagination_view = '<ul class="pagination">';
		for($i = 1; $i < $page_total + 1; $i++) {
			if($current_pagenum == $i) {
				$pagination_view .= '<li><button class="btn btn_blue active" onclick="loadReleasePage(event,'.$i.')">'.$i.'</button></li>';
			} else {
				$pagination_view .= '<li><button class="btn btn_blue" onclick="loadReleasePage(event,'.$i.')">'.$i.'</button></li>';
			}
		}
		$pagination_view .= '</ul>';
	} else {
		$pagination_view = "";
	}

	return $pagination_view;
}

if(count($allRecords) > 0 && is_array($allRecords)): ?>

	<div class="main_content_wrapper col_1">
		<div class="sub_content_wrapper">
			<div class="box_content">
				<div class="show_info custom">
					<h3>
						<?php echo $lang['mbr_submit_h_2']; ?>
					</h3>
				</div>
			</div>
			<div class="box_content">
				<table class="record">
					<thead>
					<tr>
						<td>
							<?php echo $lang['mbr_th_1']; ?>
						</td>
						<td>
							<?php echo $lang['mbr_th_2']; ?>
						</td>
						<td>
							<?php echo $lang['mbr_th_3']; ?>
						</td>
						<td>
							<?php echo $lang['mbr_th_4']; ?>
						</td>
						<td>
							<?php echo $lang['mbr_th_8']; ?>
						</td>
						<td></td>
						<td></td>
					</tr>
					</thead>
					<tbody>
					<?php foreach($allRecords as $record): ?>
						<tr id="<?php echo $record['ID_ALLVERSIONS']; ?>_record">
							<td>
								<?php echo $record['appname']; ?>
							</td>
							<td>
								<?php echo $record['version']; ?>
							</td>
							<td>
								<?php echo $record['release_date']; ?>
							</td>
							<td>
								<?php echo $record['supported_os']; ?>
							</td>
							<td>
								<?php echo ($record['dashboard_availablity'] == 1) ? "Yes" : "No"; ?>
							</td>
							<td>
								<?php echo ($record['major'] == 1) ? '<p class="small_info major">'.$lang['major_release'].'</p>' : ''; ?>
								<?php echo ($currentVersion[0]['version'] == $record['version']) ? '<p class="small_info active">'.$lang['current_release'].'</p>' : ''; ?>
							</td>
							<td class="action_input">
								<form id="<?php echo $record['ID_ALLVERSIONS']; ?>_delete" action="<?php echo $link['app-url']; ?>includes/admin.tasks.php" method="post" data-autosubmit>
									<button id="210" class="btn btn_red"  onclick="deleteRecord(<?php echo $record['ID_ALLVERSIONS']; ?>);">
										<i class="fa fa-trash"></i>
									</button>
									<input type="hidden" name="record_id" value="<?php echo $record['ID_ALLVERSIONS']; ?>">
									<input type="hidden" name="modify_type" value="delete">
								</form>

								<button class="btn btn_blue" type="submit" title="<?php echo $lang['dashboard_tooltip_2']; ?>" onclick="updateView(<?php echo $record['ID_ALLVERSIONS']; ?>);">
										<?php echo $lang['edit_icon']; ?>
								</button>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>

			</div>
			<div class="box_content">
				<?php echo release_result_pagination_generator($page_total, $current_page); ?>
			</div>

		</div>
	</div>

	<script>

		//get page(1,2,3..) addon list via ajax
		loadReleasePage = function (event, page_num) {
			event.preventDefault();
			event.stopImmediatePropagation();
			var hashedUrl = (window.location.hash).replace('#', '').split('/');
			var $reqparam = $('form[data-autosubmit][id=search_filter]').serialize();
			var url =  hashedUrl[0]+'/'+$reqparam+'&page_num='+page_num;
			window.location.hash = url;
		}

		function updateView(id) {
			$('#loading_icon').show(); //show loading icon'
			showOverlay(); //show overlay while loading
			window.location.hash = 'mbrelease_submit/update&id='+id;
		}


		var record_id;
		function deleteRecord(id) {
			var modify_confirm = confirm("<?php echo $lang['dashboard_msg_15']; ?>");
			if (modify_confirm == true) {
				hideNotification();
				$('#loading_icon').show();
				//store the record id so that we can remove the table from html
				record_id = id;
				$('form[data-autosubmit][id=' + id + '_delete]').autosubmit();
			} else {
				this.event.preventDefault(); //stop the actual form submission
			}
		}

		//Anonymous callback function for removing table row
		var record_deleted = function () {
			$('#' + record_id + "_record").html("<td><p><?php echo $lang['dashboard_msg_16']; ?></p></td><td></td><td></td><td></td><td></td><td></td><td></td>");
			$('#' + record_id + "_record").addClass('record_removed');
		}
	</script>
<?php else: ?>
<div class="main_content_wrapper col_2">
	<div class="sub_content_wrapper">
		<div class="box_content">
			<div class="show_info info_red custom">
				<h3>
					<?php echo $lang['dashboard_err_18']; ?>
				</h3>
				<p class="description"><?php echo $lang['no_record']; ?></p>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
