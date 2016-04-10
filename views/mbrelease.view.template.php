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
$dashboard = new Dashboard();

?>
<div class="main_content_wrapper col_2">
	<div class="sub_content_wrapper">
		<div class="box_content">
			<div class="show_info warning custom">
				<h3><?php echo $lang['mbr_h_1']; ?></h3>
			</div>
			<hr class="line"/>
			<table class="record">
				<tbody>
				<tr>
					<td>
						<?php echo $lang['mbr_th_1']; ?>
					</td>
					<td>
						<?php echo $mb['musicbee_download']['stable']['appname']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mbr_th_2']; ?>
					</td>
					<td>
						<?php echo $mb['musicbee_download']['stable']['version']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mbr_th_3']; ?>
					</td>
					<td>
						<?php echo $mb['musicbee_download']['stable']['release_date']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mbr_th_4']; ?>
					</td>
					<td>
						<?php echo $mb['musicbee_download']['stable']['supported_os']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mbr_th_5']; ?>
					</td>
					<td>
						<a href="<?php echo $link['release-note']; ?>"> <?php echo $lang['mbr_th_6']; ?></a>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<div class="box_content">
			<ul class="form">
				<li>
					<a href="#mbrelease_edit/stable" class="btn btn_blue">
						<?php echo $lang['mbr_btn_1']; ?>
					</a>
					<form id="stable_download_availablity"
					      action="<?php echo $link['url']; ?>includes/admin.tasks.php"
					      method="post"
					      data-autosubmit>
						<button type="submit" class="btn " onclick="$('form[data-autosubmit][id=stable_download_availablity]').autosubmit();">
							<?php
							if($mb['musicbee_download']['stable']['download']['available']) {
								echo $lang['mbr_btn_2'];
							} else {
								echo $lang['mbr_btn_5'];
							}
							?>
						</button>
						<input type="hidden" name="change_id" value="stable_download_disable" />
					</form>
				</li>
			</ul>
		</div>
	</div>
	<div class="sub_content_wrapper">
		<div class="box_content">
			<div class="show_info info_silver custom">
				<h3><?php echo $lang['mbr_h_2']; ?></h3>
			</div>
			<hr class="line"/>
			<table class="record">
				<tbody>
				<tr>
					<td>
						<?php echo $lang['mbr_th_1']; ?>
					</td>
					<td>
						<?php echo $mb['musicbee_download']['beta']['appname']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mbr_th_2']; ?>
					</td>
					<td>
						<?php echo $mb['musicbee_download']['beta']['version']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mbr_th_3']; ?>
					</td>
					<td>
						<?php echo $mb['musicbee_download']['beta']['release_date']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['mbr_th_4']; ?>
					</td>
					<td>
						<?php echo $mb['musicbee_download']['beta']['supported_os']; ?>
					</td>
				</tr>
				</tbody>
			</table>
			<hr class="line"/>
			<div class="show_info custom info_silver">
				<h3><?php echo $lang['mbr_th_7']; ?></h3>
			</div>
			<ul class="list">
				<p><?php echo $mb['musicbee_download']['beta']['message']; ?></p>
			</ul>
		</div>
		<div class="box_content">
			<ul class="form">
				<li>
					<a href="" class="btn btn_blue">
						<?php echo $lang['mbr_btn_3']; ?>
					</a>
					<form id="beta_download_availablity"
					      action="<?php echo $link['url']; ?>includes/admin.tasks.php"
					      method="post"
					      data-autosubmit>
						<button type="submit" class="btn " onclick="$('form[data-autosubmit][id=beta_download_availablity]').autosubmit();">
							<?php
							if($mb['musicbee_download']['beta']['download']['available']) {
								echo $lang['mbr_btn_4'];
							} else {
								echo $lang['mbr_btn_6'];
							}
							?>
						</button>
						<input type="hidden" name="change_id" value="beta_download_disable" />
					</form>
				</li>
			</ul>
		</div>
	</div>
</div>


<div class="space medium"></div>
<script>
	(function ($) {
		$.fn.autosubmit = function () {
			//noinspection JSUnresolvedFunction
			this.submit(function (event) {
				event.preventDefault();
				event.stopImmediatePropagation(); //This will stop the form submit twice
				var form = $(this);
				//noinspection JSUnresolvedFunction
				$.ajax({
					type: form.attr('method'),
					url: form.attr('action'),
					data: form.serialize()
				}).done(function (data) {
					notificationCallback(data);
				}).fail(function (jqXHR, textStatus, errorThrown) {
					showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "red_color");
				}).always(function () {
					$('#loading_icon').hide();
				});
			});
		}
		return false;
	})(jQuery)

	var reload_view = function (){
		loadUpdatePage((window.location.hash).replace('#', ''));
	}
</script>