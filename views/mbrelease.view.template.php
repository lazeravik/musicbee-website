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
$admin_only = true;
require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';

?>
<div class="main_content_wrapper col_1_2">
	<div class="sub_content_wrapper">
		<div class="box_content">
			<div class="show_info custom info_darkgrey">
				<h3><?php echo $lang['mbr_h_3']; ?></h3>
			</div>
			<ul class="link_list">
				<li>
					<a href="#mbrelease_all" data-href="mbrelease_all">
						<?php echo $lang['mbr_btn_20']; ?>
					</a>
					<hr class="line"/>

					<a href="#mbrelease_submit/stable" data-href="mbrelease_submit/stable">
						<?php echo $lang['mbr_btn_1']; ?>
					</a>

					<a href="#mbrelease_submit/beta" data-href="mbrelease_submit/beta">
						<?php echo $lang['mbr_btn_3']; ?>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="sub_content_wrapper">
		<div class="box_content">
			<div class="show_info custom header">
				<h3><?php echo $lang['mbr_h_1']; ?></h3>
				<form id="stable_download_availablity"
				      action="<?php echo $link['url']; ?>includes/admin.tasks.php"
				      method="post"
				      data-autosubmit>
					<button type="submit" class="btn btn_blacknwhite" onclick="$('form[data-autosubmit][id=stable_download_availablity]').autosubmit();">
						<?php
						if($mb['musicbee_download']['stable']['download']['available']) {
							echo $lang['mbr_btn_2'];
						} else {
							echo $lang['mbr_btn_5'];
						}
						?>
					</button>
					<input type="hidden" name="change_id" value="stable_download_disable"/>
				</form>
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
			<div class="show_info info_silver custom header">
				<h3><?php echo $lang['mbr_h_2']; ?></h3>
				<form id="beta_download_availablity"
				      action="<?php echo $link['url']; ?>includes/admin.tasks.php"
				      method="post"
				      data-autosubmit>
					<button type="submit" class="btn " onclick="$('form[data-autosubmit][id=beta_download_availablity]').autosubmit();">
						<?php
						if($mb['musicbee_download']['beta']['download']['available']) {
							echo $lang['mbr_btn_2'];
						} else {
							echo $lang['mbr_btn_5'];
						}
						?>
					</button>
					<input type="hidden" name="change_id" value="beta_download_disable"/>
				</form>
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
	</div>
</div>


<div class="space medium"></div>
<script>

	var reload_view = function () {
		loadUpdatePage((window.location.hash).replace('#', ''));
	}
</script>