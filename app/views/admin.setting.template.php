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
$admin_only = true; //only admin
require_once $_SERVER['DOCUMENT_ROOT'].'/app/functions.php';

include $link['root'].'classes/Dashboard.php';
?>

<!--suppress JSUnresolvedFunction -->
<div class="main_content_wrapper col_1_2">
	<div class="sub_content_wrapper">
		<div class="box_content">
			<ul class="link_list">
				<li>
					<a href="#admin_setting" data-href="admin_setting"><?php echo $lang['general']; ?></a>
					<a href="#admin_setting/links" data-href="admin_setting/links"><?php echo $lang['links']; ?></a>
					<hr class="line">
					<a href="#admin_setting/help" data-href="admin_setting/help"><?php echo $lang['help_page']; ?></a>
					<a href="#admin_setting/api" data-href="admin_setting/api"><?php echo $lang['api_page']; ?></a>
					<a href="#admin_setting/press" data-href="admin_setting/press"><?php echo $lang['press&media_page']; ?></a>
				</li>
			</ul>
		</div>
	</div>


	<div class="sub_content_wrapper" id="addon_list_fullview">
		<form action="<?php echo $link['app-url']; ?>includes/dashboard.tasks.php" method="post" id="site_setting" data-autosubmit>

			<?php if(isset($_GET['links'])): ?>
				<div class="box_content">
				<span class="show_info info_darkgrey custom header">
					<h3><?php echo $lang['dashboard_admin_header_3']; ?></h3>
				</span>
					<p class="show_info danger">
						<?php echo $lang['dashboard_admin_desc_7']; ?>
					</p>
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
					</ul>
				</div>
				<input type="hidden" name="setting_type" value="links"/>

			<?php elseif(isset($_GET['press'])): ?>
				<div class="box_content">
				<span class="show_info info_darkgrey custom header">
					<h3><?php echo $lang['dashboard_admin_header_28']; ?></h3>
				</span>
					<p class="show_info danger">
						<?php echo $lang['dashboard_admin_desc_11']; ?>
					</p>
				</div>
				<div class="box_content">
					<ul class="form">
						<li>
							<label for="wmd-input" class="wide">
								<p><?php echo $lang['dashboard_admin_header_29']; ?></p>
							</label>
							<div id="wmd-editor" class="wmd-panel">
								<div id="wmd-button-bar"></div>
								<textarea
									id="wmd-input"
									name="press_content"
									onkeyup="$('#wmd-input_count').text(5000 - this.value.length+'/5000')"><?php echo $mb['help']['press_md']['data']; ?></textarea>
							</div>
							<p id="wmd-input_count" class="counter"></p>
							<div id="wmd-preview" class="wmd-panel markdownView box"></div>
						</li>
					</ul>
				</div>

			<input type="hidden" name="setting_type" value="press"/>
				<script>
					$(document).ready(function () {
						//Markdown editor load
						MBEditor.wmdBase();
						MBEditor.Util.startEditor();
					});
				</script>

			<?php elseif(isset($_GET['api'])): ?>
				<div class="box_content">
				<span class="show_info info_darkgrey custom header">
					<h3><?php echo $lang['dashboard_admin_header_26']; ?></h3>
				</span>
					<p class="show_info danger">
						<?php echo $lang['dashboard_admin_desc_10']; ?>
					</p>
				</div>
				<div class="box_content">
					<ul class="form">
						<li>
							<label for="wmd-input" class="wide">
								<p><?php echo $lang['dashboard_admin_header_27']; ?></p>
							</label>
							<div id="wmd-editor" class="wmd-panel">
								<div id="wmd-button-bar"></div>
								<textarea
									id="wmd-input"
									name="api"
									onkeyup="$('#wmd-input_count').text(5000 - this.value.length+'/5000')"><?php echo $mb['help']['api_md']['data']; ?></textarea>
							</div>
							<p id="wmd-input_count" class="counter"></p>
							<div id="wmd-preview" class="wmd-panel markdownView box"></div>
						</li>
					</ul>
				</div>

			<input type="hidden" name="setting_type" value="api"/>
				<script>
					$(document).ready(function () {
						//Markdown editor load
						MBEditor.wmdBase();
						MBEditor.Util.startEditor();
					});
				</script>

			<?php elseif(isset($_GET['help'])): ?>
				<div class="box_content">
				<span class="show_info info_darkgrey custom header">
					<h3><?php echo $lang['dashboard_admin_header_4']; ?></h3>
				</span>
					<p class="show_info danger">
						<?php echo $lang['dashboard_admin_desc_8']; ?>
					</p>
				</div>
				<div class="box_content">
					<ul class="form">
						<li>
							<label for="faqApiLink">
								<p>API Link for FAQ Page</p>
							</label>
							<input type="text" id="faqApiLink" name="faqApiLink" value="http://musicbee.wikia.com/index.php?action=render&title=FAQ">
						</li>
					</ul>
				</div>

				<div class="box_content">
					<ul class="form">
						<li>
							<label for="help_links" class="wide">
								<p><?php echo $lang['dashboard_admin_header_24']; ?></p>
								<p class="description"><?php echo $lang['dashboard_admin_desc_9']; ?></p>
							</label>
							<textarea class="wide" id="help_links" name="help_links"><?php echo $mb['help']['help_links']['data']; ?></textarea>
						</li>
					</ul>
				</div>

				<input type="hidden" name="setting_type" value="help"/>
			<?php else: ?>

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
							<label for="unapproved_addon_max">
								<p><?php echo $lang['dashboard_admin_header_7']; ?></p>
								<p class="description"><?php echo $lang['dashboard_admin_desc_3']; ?></p>
							</label>
							<input type="number" id="unapproved_addon_max" name="unapproved_addon_max" value="<?php echo $setting['maxSubmitWithOutApproval']; ?>"/>
						</li>
						<li>
							<label for="eliteRequirement">
								<p><?php echo $lang['dashboard_admin_header_21']; ?></p>
							</label>
							<input type="number" id="eliteRequirement" name="eliteRequirement" value="<?php echo $setting['eliteRequirement']; ?>"/>
						</li>
						<li>
							<label for="selfApprovalRequirement">
								<p><?php echo $lang['dashboard_admin_header_22']; ?></p>
							</label>
							<input type="number" id="selfApprovalRequirement" name="selfApprovalRequirement" value="<?php echo $setting['selfApprovalRequirement']; ?>"/>
						</li>
						<hr class="line"/>
						<li>
							<label for="maximumAddonSubmissionPerDay">
								<p><?php echo $lang['dashboard_admin_header_23']; ?></p>
								<p class="description"><?php echo $lang['dashboard_admin_desc_6']; ?></p>
							</label>
							<input type="number" id="maximumAddonSubmissionPerDay" name="maximumAddonSubmissionPerDay" value="<?php echo $setting['maximumAddonSubmissionPerDay']; ?>"/>
						</li>
					</ul>
				</div>
				<input type="hidden" name="setting_type" value="general"/>
			<?php endif; ?>



			<div class="box_content">
				<ul class="form">
					<button class="btn btn_blue" type="submit" onclick="saveSetting()"><?php echo $lang['save']; ?></button>
				</ul>
			</div>
			<input type="hidden" name="site_setting" value="true"/>
		</form>
	</div>
</div>

<div class="space medium"></div>

<script type="text/javascript">

	function saveSetting() {
		$('form[data-autosubmit][id=site_setting]').autosubmit();
	}


	var setting_saved = function () {
		//reload_addon_approval_list_overview();
		loadUpdatePage(window.location.hash.replace('#', ''));
	}


</script>
