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
require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';

include $link['root'].'classes/Dashboard.php';
include $link['root'].'classes/Search.php';
$search = new Search();
$dashboard = new Dashboard();

?>
<div class="main_content_wrapper col_1">
	<div class="sub_content_wrapper">
		<div class="box_content">
			<div class="show_info custom">
				<h3><?php echo $lang['transfer_ownership_header']; ?></h3>
				<p class="description">
					When you transfer ownership from one person to another, the original author will lose all control over the add-on
				</p>
			</div>
		</div>

		<div class="main_content_wrapper col_2">
			<div class="sub_content_wrapper">
				<div class="box_content">
					<div class="show_info info_silverwhite custom">
						<h3><?php echo $lang['transfer_step1']; ?></h3>
					</div>
					<form id="search_filter" action="<?php echo $link['url']; ?>views/dashboard.all.template.php" method="get" data-autosubmit>
						<span class="show_info info_silverwhite custom search_container">
							<input type="search"
							       id="search_box"
							       spellcheck="false"
							       autocomplete="off"
							       autocorrect="off"
							       class="search filter_search dark"
							       name="query"
							       placeholder="<?php echo $lang['search_submitted_addons']; ?>"
							       onkeyup="autoComplete()">
							<input type="hidden" name="action" value="search">
							<div class="search_list_wrapper">
								<ul id="search_list" class="search_list"></ul>
							</div>

						</span>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	function autoComplete() {
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
	function set_item(item) {
		// change input value
		$('#search_box').val(item);
		// hide proposition list
		$('#search_list').hide();
	}
</script>