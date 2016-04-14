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
$admin_only = true; //only admin
require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';

include $link['root'].'classes/Dashboard.php';
?>

<!--suppress JSUnresolvedFunction -->
<div class="main_content_wrapper col_2_1">
	<div class="sub_content_wrapper" id="addon_list_fullview">
		<form action="<?php echo $link['url']; ?>includes/dashboard.tasks.php" method="post" id="site_setting" data-autosubmit>
			<div class="box_content">
				<span class="show_info info_darkgrey custom header">
					<h3><?php echo $lang['dashboard_admin_header_2']; ?></h3>
				</span>
				<p class="show_info danger">
					<?php echo $lang['dashboard_admin_desc_5']; ?>
				</p>
			</div>

			<div class="box_content">
				<ul class="form">
					<li>
						<label for="pageloadtime">
							<p><?php echo $lang['dashboard_admin_header_5']; ?></p>
							<p class="description"><?php echo $lang['dashboard_admin_desc_1']; ?></p>
						</label>
						<div class="right_toogle">
							<input class="cmn-toggle cmn-toggle-round-flat" type="checkbox" id="pageloadtime" name="pageloadtime" <?php echo ($setting['showPgaeLoadTime']) ? 'checked' : ''; ?>/>
							<label for="pageloadtime"></label>
						</div>
					</li>
					<li>
						<label for="submission">
							<p><?php echo $lang['dashboard_admin_header_6']; ?></p>
							<p class="description"><?php echo $lang['dashboard_admin_desc_2']; ?></p>
						</label>
						<div class="right_toogle">
							<input class="cmn-toggle cmn-toggle-round-flat" type="checkbox" id="submission" name="submission" <?php echo ($setting['addonSubmissionOn']) ? 'checked' : ''; ?>/>
							<label for="submission"></label>
						</div>
					</li>
					<li>
						<label for="unapproved_addon_max">
							<p><?php echo $lang['dashboard_admin_header_7']; ?></p>
							<p class="description"><?php echo $lang['dashboard_admin_desc_3']; ?></p>
						</label>
						<input type="number" id="unapproved_addon_max" name="unapproved_addon_max" value="<?php echo $setting['maxSubmitWithOutApproval']; ?>"/>

					</li>
				</ul>

			</div>

			<div class="box_content">
				<ul class="form">
					<li>
						<label for="imguron">
							<p><?php echo $lang['dashboard_admin_header_8']; ?></p>
							<p class="description"><?php echo $lang['dashboard_admin_desc_4']; ?></p>
						</label>
						<div class="right_toogle">
							<input class="cmn-toggle cmn-toggle-round-flat" type="checkbox" id="imguron" name="imguron" <?php echo ($setting['imgurUploadOn']) ? 'checked' : ''; ?>/>
							<label for="imguron"></label>
						</div>
					</li>
					<li>
						<label for="imgurClientID">
							<p><?php echo $lang['dashboard_admin_header_9']; ?></p>
						</label>
						<input type="text" id="imgurClientID" name="imgurClientID" value="<?php echo $setting['imgurClientID']; ?>"/>
					</li>
					<li>
						<label for="imgurClientSecret">
							<p><?php echo $lang['dashboard_admin_header_10']; ?></p>
						</label>
						<input type="text" id="imgurClientSecret" name="imgurClientSecret" value="<?php echo $setting['imgurClientSecret']; ?>"/>
					</li>
				</ul>
			</div>

			<div class="box_content">
				<ul class="form">
					<li>
						<label for="paypalDonationLink">
							<p><?php echo $lang['dashboard_admin_header_11']; ?></p>
						</label>
						<input type="text" id="paypalDonationLink" name="paypalDonationLink" value="<?php echo $setting['paypalDonationLink']; ?>"/>
					</li>
					<li>
						<label for="twitterLink">
							<p><?php echo $lang['dashboard_admin_header_12']; ?></p>
						</label>
						<input type="text" id="twitterLink" name="twitterLink" value="<?php echo $setting['twitterLink']; ?>"/>
					</li>
					<li>
						<label for="wikiaLink">
							<p><?php echo $lang['dashboard_admin_header_14']; ?></p>
						</label>
						<input type="text" id="wikiaLink" name="wikiaLink" value="<?php echo $setting['wikiaLink']; ?>"/>
					</li>
					<li>
						<label for="wishlistLink">
							<p><?php echo $lang['dashboard_admin_header_15']; ?></p>
						</label>
						<input type="text" id="wishlistLink" name="wishlistLink" value="<?php echo $setting['wishlistLink']; ?>"/>
					</li>
					<li>
						<label for="websiteApiLink">
							<p><?php echo $lang['dashboard_admin_header_16']; ?></p>
						</label>
						<input type="text" id="websiteApiLink" name="websiteApiLink" value="<?php echo $setting['websiteApiLink']; ?>"/>
					</li>
					<li>
						<label for="musicbeeApiLink">
							<p><?php echo $lang['dashboard_admin_header_17']; ?></p>
						</label>
						<input type="text" id="musicbeeApiLink" name="musicbeeApiLink" value="<?php echo $setting['musicbeeApiLink']; ?>"/>
					</li>
					<li>
						<label for="websiteBugLink">
							<p><?php echo $lang['dashboard_admin_header_18']; ?></p>
						</label>
						<input type="text" id="websiteBugLink" name="websiteBugLink" value="<?php echo $setting['websiteBugLink']; ?>"/>
					</li>
					<li>
						<label for="musicbeeBugLink">
							<p><?php echo $lang['dashboard_admin_header_19']; ?></p>
						</label>
						<input type="text" id="musicbeeBugLink" name="musicbeeBugLink" value="<?php echo $setting['musicbeeBugLink']; ?>"/>
					</li>

					<li>
						<label for="presskitLink">
							<p><?php echo $lang['dashboard_admin_header_20']; ?></p>
						</label>
						<input type="text" id="presskitLink" name="presskitLink" value="<?php echo $setting['presskitLink']; ?>"/>
					</li>
				</ul>
			</div>
			<div class="box_content">
				<ul class="form">
					<li>
						<label for="eliteRequirement">
							<p><?php echo $lang['dashboard_admin_header_21']; ?></p>
						</label>
						<input type="text" id="eliteRequirement" name="eliteRequirement" value="<?php echo $setting['eliteRequirement']; ?>"/>
					</li>
					<li>
						<label for="selfApprovalRequirement">
							<p><?php echo $lang['dashboard_admin_header_22']; ?></p>
						</label>
						<input type="text" id="selfApprovalRequirement" name="selfApprovalRequirement" value="<?php echo $setting['selfApprovalRequirement']; ?>"/>
					</li>
				</ul>
			</div>

			<div class="box_content">
				<ul class="form">
					<button class="btn btn_blue" type="submit" onclick="saveSetting()"><?php echo $lang['dashboard_admin_button_1']; ?></button>
				</ul>
			</div>
			<input type="hidden" name="site_setting" value="true"/>
		</form>
	</div>
</div>

<div class="space medium"></div>

<script type="text/javascript">
	//	//sidebar link redirect and ajax load
	//	$('#ajax_area a[data-load-page]').on('click', function (e) {
	//		e.preventDefault();
	//		/* Act on the event */
	//		loadPageGet(generateUrl($(this).attr('data-href')), (!!$(this).attr('data-get-req')) ? $(this).attr('data-get-req') : "");
	//		window.location.hash = $(this).attr('data-href');
	//	});
	//
	function saveSetting() {
		$('form[data-autosubmit][id=site_setting]').autosubmit();
	}


	var setting_saved = function () {
		//reload_addon_approval_list_overview();
		var $generatedUrl = generatePageUrl(window.location.hash.replace('#', ''));
		loadPageGet($generatedUrl, "action=submit_success");
	}


</script>