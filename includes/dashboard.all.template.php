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
	require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';

	include $siteRoot . 'classes/Addon.php';
	$addon = new addon(); //create an instance of the addondashboard class

	$addonInfo = $addon->getAddonListByMember($context['user']['id'], 100);
?>


<div class="content_inner_wrapper_admin width100">
	<div class="admin_margin_wrapper">
		<div class="infocard_header">
			<h3><?php echo $lang['228']; ?></h3>
			<p></p>
		</div>
		<table class="allrelease">
			<?php if ($addonInfo != null): ?>
				<thead>
				<tr>
					<th><?php echo $lang['229']; ?></th>
					<th><?php echo $lang['230']; ?></th>
					<th><?php echo $lang['231']; ?></th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<?php
					//Iterate thorugh all og\f the release record and show them
					foreach ($addonInfo as $record) {
						echo "
					<tr id=\"" . $record['ID_ADDON'] . "_tbl\">
						<td><a href='" . $link['addon']['home'] . $record['ID_ADDON'] . "/" . Format::Slug($record['addon_title']) . "' target=\"_blank\">" . $record['addon_title'] . "</a></td>
						<td>" . Format::UnslugTxt($record['addon_type']) . "</td>
						<td>" . Addon::getStatus($record['status']) . "</td>
						<td class=\"button_section\">
							<button id=\"" . $record['ID_ADDON'] . "_edit\" class=\"entry_edit\" title=\"Modify info\" onclick=\"showEditModal(" . $record['ID_ADDON'] . ");\">" . $lang['234'] . "</button>

							<form id=\"" . $record['ID_ADDON'] . "\" action=\"../includes/dashboard.tasks.php\" method=\"post\" data-autosubmit>
								<button id=\"" . $record['ID_ADDON'] . "_remove\" class=\"entry_remove\" title=\"" . $lang['233'] . "\" onclick=\"deleteRecord();\" ><i class=\"fa fa-trash\"></i></button>
								<input type=\"hidden\" name=\"record_id\" value=\"" . $record['ID_ADDON'] . "\" />
								<input type=\"hidden\" name=\"modify_type\" value=\"delete\" />
							</form>

						</td>
					</tr>";
					};
				?>
				</tbody>
			<?php else: ?>
				<p>You have not submitted any addon yet!</p>
			<?php endif; ?>
		</table>
	</div>
</div>
<div id="clear"></div>
<div id="editView" class="modalBox1 iw-modalBox fadeIn animated"></div>
<script type="text/javascript">
	function showEditModal(id) {
		$('.modalBox1').modalBox({
			left: '0',
			top: '0',
			width: '100%',
			height: '100%',
			keyClose: true,
			iconClose: true,
			bodyClose: true,
			onOpen: function () {
				$('#editView').html("<div class=\"sk-circle\"> <div class=\"sk-circle1 sk-child\"></div> <div class=\"sk-circle2 sk-child\"></div> <div class=\"sk-circle3 sk-child\"></div> <div class=\"sk-circle4 sk-child\"></div> <div class=\"sk-circle5 sk-child\"></div> <div class=\"sk-circle6 sk-child\"></div> <div class=\"sk-circle7 sk-child\"></div> <div class=\"sk-circle8 sk-child\"></div> <div class=\"sk-circle9 sk-child\"></div> <div class=\"sk-circle10 sk-child\"></div> <div class=\"sk-circle11 sk-child\"></div> <div class=\"sk-circle12 sk-child\"></div> </div>"); //show loading signal maybe!
				loadEditView(id); //do some ajax request for the file
			},
			onClose: function () {
				$('#editView').html(""); //delete the html we got from ajax req
			}
		});
	}

	function loadEditView(id) {
		$.fx.off = true; // turn off jquery animation effects
		$.ajax({
			url: '<?php $_SERVER['DOCUMENT_ROOT']; ?>/includes/dashboard.submit.template.php?view=update&id=' + id,
			cache: false,
			type: "POST",
		}).done(function (data) {
			if ($('#editView').children().length > 0) {
				$('#editView').html(data);
				hideNotification();
			}
		}).fail(function (jqXHR, textStatus, errorThrown) {
			showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "error", "red_color");
		}).always(function () {
		});
	}

	$delete_record_id = "0";
	function deleteRecord() {
		var modify_confirm = confirm("<?php echo $lang['232']; ?>");
		if (modify_confirm == true) {
			hideNotification();
			$('#loading_icon').show();
			//store the record id so that we can remove the table from html
			$delete_record_id = $('form[data-autosubmit]').attr('id');
			$('form[data-autosubmit]').autosubmit();
		} else {
			this.event.preventDefault(); //stop the actual form submission
		}
	}
	(function ($) {
		$.fn.autosubmit = function () {
			this.submit(function (event) {
				event.preventDefault();
				event.stopImmediatePropagation(); //This will stop the form submit twice
				var form = $(this);
				$.ajax({
					type: form.attr('method'),
					url: form.attr('action'),
					data: form.serialize()
				}).done(function (data) {
					notificationCallback(data);
				}).fail(function (jqXHR, textStatus, errorThrown) {
					showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "error", "red_color");
				}).always(function () {
					$('#loading_icon').hide();
				});
			});
		}
		return false;
	})(jQuery)


	var remove_addon_record = function () {
		$('#' + $delete_record_id + "_tbl").html("");
		$('#' + $delete_record_id + "_tbl").addClass('record_removed');
	}


</script>