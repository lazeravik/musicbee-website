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

if(isset($_GET['stable']))
{
	$release_type = "stable";
}
elseif(isset($_GET['beta']))
{
	$release_type = "beta";
}
elseif(isset($_GET['patch']))
{
	$release_type = "patch";
}
elseif(isset($_GET['update']) && isset($_GET['id']))
{
	$release_type = "update";
	$id = $_GET['id'];
	$data = getVersionInfo($id, "byId")[0]; //Request version info by ID
	$currentVersion = getVersionInfo(0, "byCurrentVersion")[0]; //Get the current
}
?>
<?php if($release_type == "update") { ?>
	<div class="main_content_wrapper col_2_1">
		<div class="sub_content_wrapper">
			<form action="<?php echo $link['app-url']; ?>includes/admin.tasks.php" method="post" id="release_submit" data-autosubmit>
				<div class="box_content">
					<div class="show_info custom">
						<h3><?php echo $lang['mbr_submit_h_2']; ?></h3>
					</div>
				</div>

				<div class="box_content">
					<ul class="form">
						<li>
							<label for="appname">
								<p><?php echo $lang['mbr_lbl_1']; ?></p>
							</label>
							<input type="text" name="appname" value="<?php echo $data['appname']; ?>" id="appname" placeholder="<?php echo $lang['mbr_placeholder_1']; ?>"/>
						</li>
						<li>
							<label for="ver">
								<p><?php echo $lang['mbr_lbl_2']; ?></p>
							</label>
							<input type="text" name="ver" value="<?php echo $data['version']; ?>" id="ver" placeholder="<?php echo $lang['mbr_placeholder_2']; ?>"/>
						</li>
						<li>
							<label for="os">
								<p><?php echo $lang['mbr_lbl_3']; ?></p>
							</label>
							<input type="text" name="os" value="<?php echo $data['supported_os']; ?>" id="os" placeholder="<?php echo $lang['mbr_placeholder_3']; ?>"/>
						</li>
					</ul>
					<hr class="line"/>
					<ul class="form">
						<li>
							<label for="major">
								<p><?php echo $lang['mbr_lbl_10']; ?></p>
								<p class="description"><?php echo $lang['mbr_desc_1']; ?></p>
							</label>
							<div class="right_toogle">
								<input class="cmn-toggle cmn-toggle-round-flat" type="checkbox" id="major" name="major" <?php echo ($data['major'])? 'checked' :'' ; ?>/>
								<label for="major"></label>
							</div>
						</li>

						<li>
							<label for="dashboard">
								<p><?php echo $lang['mbr_lbl_11']; ?></p>
							</label>
							<div class="right_toogle">
								<input class="cmn-toggle cmn-toggle-round-flat" type="checkbox" id="dashboard" name="dashboard" <?php echo ($data['dashboard_availablity'])? 'checked' :'' ; ?>/>
								<label for="dashboard"></label>
							</div>
						</li>
					</ul>
				</div>

				<?php if($data['version']==$currentVersion['version']) : ?>
				<div class="box_content">
					<ul class="form">
						<li>
							<label for="ilink1">
								<p><?php echo $lang['mbr_lbl_4']; ?></p>
							</label>
							<input type="url/text" name="ilink1" value="<?php echo $currentVersion['DownloadLink']; ?>" id="ilink1" placeholder="<?php echo $lang['mbr_placeholder_4']; ?>"/>
						</li>
						<li>
							<label for="ilink2">
								<p><?php echo $lang['mbr_lbl_5']; ?></p>
							</label>
							<input type="text" name="ilink2" value="<?php echo $currentVersion['MirrorLink1']; ?>" id="ilink2" placeholder="<?php echo $lang['mbr_placeholder_4']; ?>"/>
						</li>
						<li>
							<label for="ilink3">
								<p><?php echo $lang['mbr_lbl_6']; ?></p>
							</label>
							<input type="text" name="ilink3" value="<?php echo $currentVersion['MirrorLink2']; ?>" id="ilink3" placeholder="<?php echo $lang['mbr_placeholder_4']; ?>"/>
						</li>
					</ul>
					<hr class="line"/>
					<ul class="form">
						<li>
							<label for="plink1">
								<p><?php echo $lang['mbr_lbl_7']; ?></p>
							</label>
							<input type="url/text" name="plink1" value="<?php echo $currentVersion['PortableLink']; ?>" id="plink1" placeholder="<?php echo $lang['mbr_placeholder_4']; ?>"/>
						</li>
					</ul>
				</div>

				<?php endif; ?>

				<div class="box_content">
					<ul class="form">
						<li>
							<label for="wmd-input">
								<p><?php echo $lang['mbr_lbl_8']; ?></p>
							</label>
							<div id="wmd-editor" class="wmd-panel">
								<div id="wmd-button-bar"></div>
								<textarea id="wmd-input" name="note"><?php echo $data['release_note']; ?></textarea>
							</div>
							<div id="wmd-preview" class="wmd-panel markdownView"></div>
						</li>
					</ul>
				</div>
				<div class="box_content">
					<ul class="form">
						<li>
							<button class="btn btn_blue" type="submit" onclick="mb_submit()"><?php echo $lang['home_30']; ?></button>
						</li>
					</ul>
				</div>
				<input type="hidden" name="save" value="stable"/>
				<input type="hidden" name="id_allversion" value="<?php echo $data['ID_ALLVERSIONS']; ?>">
			</form>
		</div>
	</div>

	<div class="space medium"></div>

	<script>
		$(document).ready(function () {
			//Markdown editor load
			MBEditor.wmdBase();
			MBEditor.Util.startEditor();
		});

		function mb_submit() {
			$('form[data-autosubmit][id=release_submit]').autosubmit();
		}

		function view_list() {
			window.location.hash = 'mbrelease_all';
		}
	</script>

<?php } elseif($release_type == 'patch') { ?>
	<div class="main_content_wrapper col_2_1">
		<div class="sub_content_wrapper">
			<form action="<?php echo $link['app-url']; ?>includes/admin.tasks.php" method="post" id="release_submit" data-autosubmit>
				<div class="box_content">
				<span class="show_info custom">
					<h3><?php echo $lang['new_patch_release']; ?></h3>
					<p class="description"><?php echo $lang['patch_submission_desc']; ?></p>
				</span>
				</div>

				<div class="box_content">
					<span class="show_info info_silver custom"><?php echo $lang['appname_patch_desc']; ?></span>
					<ul class="form">
						<li>
							<label for="appname">
								<p><?php echo $lang['mbr_lbl_1']; ?></p>
							</label>
							<input type="text" value="<?php echo $mb['musicbee_download']['stable']['appname'];?>" id="appname" readonly/>
						</li>
						<li>
							<label for="os">
								<p><?php echo $lang['mbr_lbl_3']; ?></p>
							</label>
							<input type="text" value="<?php echo $mb['musicbee_download']['stable']['supported_os'];?>" id="os" readonly/>
						</li>
					</ul>
				</div>

				<div class="box_content">
					<span class="show_info warning custom"><?php echo $lang['ver_patch_desc']; ?></span>
					<ul class="form">
						<li>
							<label for="ver">
								<p><?php echo $lang['patch_ver']; ?></p>
								<p class="description">
									<b><?php echo $lang['current_stable_version']; ?> </b><?php echo $mb['musicbee_download']['stable']['version'];?>

									<?php if($mb['musicbee_download']['patch'] != null): ?>
										<br>
										<b><?php echo $lang['latest_patch_version']; ?> </b><?php echo $mb['musicbee_download']['patch']['version'];?>
									<?php endif; ?>
								</p>
							</label>
							<input type="text" name="ver" value="" id="ver" placeholder="<?php echo $lang['mbr_placeholder_2']; ?>"/>
						</li>
						<li>
							<label for="link">
								<p><?php echo $lang['download_link']; ?></p>
							</label>
							<input type="url/text" name="link" value="" id="link" placeholder="<?php echo $lang['mbr_placeholder_4']; ?>"/>
						</li>
				</div>

				<div class="box_content">
					<ul class="form">
						<li>
							<button class="btn btn_blue" type="submit" onclick="mb_submit()"><?php echo $lang['home_30']; ?></button>
						</li>
					</ul>
				</div>


				<input type="hidden" name="save" value="<?php echo $release_type; ?>"/>
				<input type="hidden" name="isnew" value="true"/>
			</form>
		</div>
	</div>
	<div class="space medium"></div>

	<script>
		function mb_submit() {
			$('form[data-autosubmit][id=release_submit]').autosubmit();
		}

		var go_to = function () {
			window.location.hash = 'mbrelease_view';
		}


	</script>
<?php } else { ?>
	<!--suppress JSUnresolvedFunction -->
	<div class="main_content_wrapper col_2_1">
		<div class="sub_content_wrapper">
			<form action="<?php echo $link['app-url']; ?>includes/admin.tasks.php" method="post" id="release_submit" data-autosubmit>
				<div class="box_content">
				<span class="show_info custom">
					<h3><?php echo $lang['mbr_submit_h_1']; ?></h3>
				</span>
				</div>

				<div class="box_content">
					<ul class="form">
						<li>
							<label for="appname">
								<p><?php echo $lang['mbr_lbl_1']; ?></p>
							</label>
							<input type="text" name="appname" value="" id="appname" placeholder="<?php echo $lang['mbr_placeholder_1']; ?>"/>
						</li>
						<li>
							<label for="ver">
								<p><?php echo $lang['mbr_lbl_2']; ?></p>
							</label>
							<input type="text" name="ver" value="" id="ver" placeholder="<?php echo $lang['mbr_placeholder_2']; ?>"/>
						</li>
						<li>
							<label for="os">
								<p><?php echo $lang['mbr_lbl_3']; ?></p>
							</label>
							<input type="text" name="os" value="" id="os" placeholder="<?php echo $lang['mbr_placeholder_3']; ?>"/>
						</li>
					</ul>
					<?php if($release_type == "stable") : ?>
						<hr class="line"/>
						<ul class="form">
							<li>
								<label for="major">
									<p><?php echo $lang['mbr_lbl_10']; ?></p>
									<p class="description"><?php echo $lang['mbr_desc_1']; ?></p>
								</label>
								<div class="right_toogle">
									<input class="cmn-toggle cmn-toggle-round-flat" type="checkbox" id="major" name="major"/>
									<label for="major"></label>
								</div>
							</li>
						</ul>
					<?php endif; ?>
				</div>

				<div class="box_content">
					<ul class="form">
						<li>
							<label for="ilink1">
								<p><?php echo $lang['mbr_lbl_4']; ?></p>
							</label>
							<input type="url/text" name="ilink1" value="" id="ilink1" placeholder="<?php echo $lang['mbr_placeholder_4']; ?>"/>
						</li>
						<?php if($release_type != "beta") : ?>
							<li>
								<label for="ilink2">
									<p><?php echo $lang['mbr_lbl_5']; ?></p>
								</label>
								<input type="text" name="ilink2" value="" id="ilink2" placeholder="<?php echo $lang['mbr_placeholder_4']; ?>"/>
							</li>
							<li>
								<label for="ilink3">
									<p><?php echo $lang['mbr_lbl_6']; ?></p>
								</label>
								<input type="text" name="ilink3" value="" id="ilink3" placeholder="<?php echo $lang['mbr_placeholder_4']; ?>"/>
							</li>
						<?php endif; ?>
					</ul>
					<?php if($release_type != "beta") : ?>
						<hr class="line"/>
						<ul class="form">
							<li>
								<label for="plink1">
									<p><?php echo $lang['mbr_lbl_7']; ?></p>
								</label>
								<input type="url/text" name="plink1" value="" id="plink1" placeholder="<?php echo $lang['mbr_placeholder_4']; ?>"/>
							</li>
						</ul>
					<?php endif; ?>
				</div>

				<div class="box_content">
					<?php if($release_type == "beta") : ?>
						<ul class="form">
							<li>
								<label for="message">
									<p><?php echo $lang['mbr_lbl_9']; ?></p>
								</label>
								<textarea id="message" name="message"></textarea>
							</li>
						</ul>
						<hr class="line"/>
					<?php endif; ?>
					<?php if($release_type == "stable") : ?>
						<ul class="form">
							<li>
								<label for="wmd-input">
									<p><?php echo $lang['mbr_lbl_8']; ?></p>
								</label>
								<div id="wmd-editor" class="wmd-panel">
									<div id="wmd-button-bar"></div>
									<textarea id="wmd-input" name="note"></textarea>
								</div>
								<div id="wmd-preview" class="wmd-panel markdownView"></div>
							</li>
						</ul>
					<?php endif; ?>
				</div>
				<div class="box_content">
					<ul class="form">
						<li>
							<button class="btn btn_blue" type="submit" onclick="mb_submit()"><?php echo $lang['home_30']; ?></button>
						</li>
					</ul>
				</div>


				<input type="hidden" name="save" value="<?php echo $release_type; ?>"/>
				<input type="hidden" name="isnew" value="true"/>
			</form>
		</div>
	</div>

	<div class="space medium"></div>

	<script>
		$(document).ready(function () {
			//Markdown editor load
			<?php if($release_type == "stable") : ?>
			MBEditor.wmdBase();
			MBEditor.Util.startEditor();
			<?php endif; ?>
		});

		function mb_submit() {
			$('form[data-autosubmit][id=release_submit]').autosubmit();
		}

		var view_list = function () {
			window.location.hash = 'mbrelease_all';
		}


	</script>
<?php } ?>
