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

?>
<div class="main_content_wrapper col_2">
	<div class="sub_content_wrapper">
		<div class="box_content">
			<div class="show_info custom">
				<h3><?php echo $lang['transfer_ownership_header']; ?></h3>
				<p class="description">
					<?php echo $lang['transfer_ownership_desc']; ?>
				</p>
			</div>
		</div>
				<div class="box_content">
					<div class="show_info info_silverwhite custom">
						<h3><?php echo $lang['transfer_step1']; ?></h3>
					</div>
						<span class="show_info info_silverwhite custom search_container">
							<input type="search"
							       id="search_box"
							       spellcheck="false"
							       autocomplete="off"
							       autocorrect="off"
							       class="search filter_search dark"
							       name="query"
							       placeholder="<?php echo $lang['search_submitted_addons']; ?>"
							       onkeyup="autoCompleteAddonlist()">
							<input type="hidden" name="action" value="search">
							<div class="search_list_wrapper">
								<ul id="search_list" class="search_list"></ul>
							</div>
						</span>
				</div>

			<form action="<?php echo $link['app-url']; ?>includes/dashboard.tasks.php" method="post" id="addon_transfer" data-autosubmit>
				<div class="box_content" id="step2_box" style="display:none">
					<div class="show_info info_silverwhite custom">
						<h3><?php echo $lang['transfer_step2']; ?></h3>
					</div>
						<span class="show_info info_silverwhite custom search_container">
							<input type="text"
							       id="search_box_user"
							       spellcheck="false"
							       autocomplete="off"
							       autocorrect="off"
							       class="search filter_search dark no_icon"
							       name="user_id_query"
							       onkeyup="autoCompleteUserlist()"
							       placeholder="<?php echo $lang['search_by_username']; ?>" required="true">
							       <input id="id_field" type="hidden" name="addon_id" value="">
									<input id="id_field_user" type="hidden" name="user_id" value="">
							       <input type="hidden" name="addon_transfer" value="true">
							<div class="search_list_wrapper">
								<ul id="user_search_list" class="search_list"></ul>
							</div>
						</span>
				</div>
				<div class="box_content" id="final_step_box" style="display:none">
					<ul class="form">
						<li>
							<button class="btn btn_blue"
							        type="submit"
							        onclick="saveEdit()"><?php echo $lang['home_30']; ?></button>
						</li>
					</ul>
				</div>
			</form>
			<div class="space medium"></div>
			</div>
		</div>


<script>
	function autoCompleteUserlist() {
		var min_length = 2; // min caracters to display the autocomplete
		var keyword = $('#search_box_user').val();
		if (keyword.length >= min_length)
		{
			$.ajax({
				url: '<?php echo $link['url']; ?>api/1.0/?type=html&action=user-search-autocomplete&limit=1&search='+keyword,
				type: 'GET',
				//data: {keyword:keyword},
				success:function(data)
				{
					$('#user_search_list').show();
					$('#user_search_list').html(data);
				}
			});
		} else {
			$('#user_search_list').hide();
		}
	}

	function autoCompleteAddonlist() {
		var min_length = 2; // min caracters to display the autocomplete
		var keyword = $('#search_box').val();
		if (keyword.length >= min_length)
		{
			$.ajax({
				url: '<?php echo $link['url']; ?>api/1.0/?type=html&action=addon-search-autocomplete&page=1&limit=10&search='+keyword,
				type: 'GET',
				//data: {keyword:keyword},
				success:function(data)
				{
					$('#search_list').show();
					$('#search_list').html(data);
				}
			});
		} else {
			$('#search_list').hide();
		}
	}

	// set_item : this function will be executed when we select an item
	function set_item(uid, event) {
		event.preventDefault();
		event.stopImmediatePropagation(); //This will stop the form submit twice
		var item = $("#"+uid);
		$("#"+uid +" button").remove();
		$("#search_box").remove();
		$("#search_list").html(item);
		$("#search_list").addClass("selected");
		item.addClass("active");

		var addon_id = $("#"+uid+" input[name=addon_id]");
		$("#id_field").val(addon_id.val());

		$("#step2_box").show();
	}

	// set_item : this function will be executed when we select an item
	function set_user(uid, event) {
		event.preventDefault();
		event.stopImmediatePropagation(); //This will stop the form submit twice
		var item = $("#"+uid);
		$("#"+uid +" button").remove();
		$("#search_box_user").remove();
		$("#user_search_list").html(item);
		$("#user_search_list").addClass("selected");
		item.addClass("active");

		var user_id = $("#"+uid+" input[name=user_id]");
		$("#id_field_user").val(user_id.val());


		$("#final_step_box").show();
	}

	function saveEdit() {
		$('form[data-autosubmit][id=addon_transfer]').autosubmit();
	}

	var transfer_success = function() {
		var dataUrl =window.location.hash.replace('#', '');
		loadUpdatePage(dataUrl);
	}

</script>
