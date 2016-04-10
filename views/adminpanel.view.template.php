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

	$admin_only = true; //only for admins
	require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
	require_once $link['root'] . 'includes/admin.tasks.php';
	$safeGet = (isset($_GET['view'])) ? $_GET['view'] : "";
	if (($safeGet) == "all") : ?>
		<div class="admin_margin_wrapper">
			<div class="infocard_header ">
				<h3>All MusicBee releases</h3>
			</div>
	<span class="show_info">
		<p class="active">Green</p> = Current Active Version, &nbsp;&nbsp;&nbsp;<p class="major">major</p> = Major Release
	</span>

			<?php
				/**
				 * "All MusicBee Release" page View. also modal box for updating is placed here
				 */
				$currentVersion = getVersionInfo(0, "byCurrentVersion"); //Get the current stable release info
				$allRecords = getAllVersion(); // Get all MusicBee release versions till now
				//We did released some MusicBee version before right?
				if (count($allRecords) > 0 && is_array($allRecords)) {
					echo "<table class=\"allrelease\">
			<thead>
				<tr>
					<th>Appname</th>
					<th>Version</th>
					<th>Release Type</th>
					<th>Supported OS</th>
					<th>Release Date</th>
					<th>Available on Dashboard?</th>
					<th></th>
				</tr>
			</thead>
			<tbody>";
					//Iterate thorugh all og\f the release record and show them
					foreach ($allRecords as $record) {
						$activeRecordClass = ($currentVersion[0]['version'] == $record['version']) ? "active_record" : "";
						echo "
					<tr id=\"" . $record['ID_ALLVERSIONS'] . "_tbl\" class=\"", $activeRecordClass, "\">
						<td>" . $record['appname'] . "</td>
						<td>" . $record['version'] . "</td>
						<td>", ($record['major'] == 1) ? "<p class='major'>major</p>" : "...";
						echo "</td>
							<td>" . $record['supported_os'] . "</td>
							<td>" . $record['release_date'] . "</td>
							<td>", ($record['dashboard_availablity'] == 1) ? "Yes" : "No";
						echo "</td>
							<td class=\"button_section\">
								<button id=\"" . $record['ID_ALLVERSIONS'] . "_edit\" class=\"entry_edit\" title=\"Modify info\" onclick=\"showEditModal(" . $record['ID_ALLVERSIONS'] . ");\"><i class=\"fa fa-pencil\"></i></button>";
						if ($currentVersion[0]['version'] == $record['version']) {
							echo "<button id=\"" . $record['ID_ALLVERSIONS'] . "_remove\" class=\"entry_remove\" title=\"You can not delete the current version!\" disabled><i class=\"fa fa-trash\"></i></button>";
						} else {
							echo "
									<form id=\"" . $record['ID_ALLVERSIONS'] . "\" action=\"../includes/admin.tasks.php\" method=\"post\" data-autosubmit>
										<button id=\"" . $record['ID_ALLVERSIONS'] . "_remove\" class=\"entry_remove\" title=\"Remove this Version Permanently\" onclick=\"modify();\" ><i class=\"fa fa-trash\"></i></button>
										<input type=\"hidden\" name=\"record_id\" value=\"" . $record['ID_ALLVERSIONS'] . "\" />
										<input type=\"hidden\" name=\"modify_type\" value=\"delete\" />
									</form>";
						}
						echo "</td></tr>";
					}
					echo "</tbody></table>";

				} /* Wait, we haven't record any MusicBee version?? Show error message! */
				elseif (is_string($allRecords))
					echo "<p class=\"show_info\">" . $allRecords . "</p>"; ?>
			<div id="editView" class="modalBox iw-modalBox fadeIn animated"></div>
			<script type="text/javascript">
				function showEditModal(id) {
					$('.modalBox').modalBox({
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
						url: '<?php echo $link['url']; ?>views/adminpanel.edit.template.php?view=update&id=' + id,
						cache: false,
						type: "POST",
					}).done(function (data) {
						if ($('#editView').children().length > 0) {
							$('#editView').html(data);
							hideNotification();
						}
					}).fail(function (jqXHR, textStatus, errorThrown) {
						showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "red_color");
					}).always(function () {
					});
				}

				function modify() {
					hideNotification();
					$('#loading_icon').show();
					$('form[data-autosubmit]').autosubmit();
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
								removeRecordTbl(form.attr('id'));
							}).fail(function (jqXHR, textStatus, errorThrown) {
								showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "red_color");
							}).always(function () {
								$('#loading_icon').hide();
							});
						});
					}
					return false;
				})(jQuery)

				function removeRecordTbl(id) {
					$('#' + id + "_tbl").html("");
					$('#' + id + "_tbl").addClass('record_removed');
				}
			</script>
		</div>
		<?php
	/**
	 * Stable and beta release view. This is used in the default admin panel view
	 */
	else: ?>
		<div class="content_inner_wrapper_admin">
			<div class="admin_margin_wrapper">
				<div class="infocard_header green_color">
					<h3>Current MusicBee <b>Stable Release</b> Info</h3>
				</div>

				<div class="info_table_wrap">
					<div class="info_table_left_wrap"><h4><b>App Name</b></h4></div>
					<div class="info_table_right_wrap"><?php echo $release['stable']['appname']; ?></div>
					<div id="clear"></div>
				</div>
				<div class="info_table_wrap">
					<div class="info_table_left_wrap"><h4><b>Version</b></h4></div>
					<div class="info_table_right_wrap"><?php echo $release['stable']['version']; ?></div>
					<div id="clear"></div>
				</div>
				<div class="info_table_wrap">
					<div class="info_table_left_wrap"><h4><b>Released on</b></h4></div>
					<div class="info_table_right_wrap"><?php echo $release['stable']['date']; ?></div>
					<div id="clear"></div>
				</div>
				<div class="info_table_wrap">
					<div class="info_table_left_wrap"><h4><b>Supported OS</b></h4></div>
					<div class="info_table_right_wrap"><?php echo $release['stable']['os']; ?></div>
					<div id="clear"></div>
				</div>
				<div class="info_table_wrap">
					<div class="info_table_left_wrap"><h4><b>Release Note</b></h4></div>
					<div class="info_table_right_wrap">
						<a href="<?php echo $link['release-note']; ?>" target="_blank">View release notes here ></a>
					</div>
					<div id="clear"></div>
				</div>

				<h3 class="info_editor_header teal_color"><i class="fa fa-link"></i>&nbsp; Installer Download Links</h3>
				<div class="info_table_wrap">
					<div class="info_table_left_wrap"><h4><b>Download Link:</b></h4></div>
					<div class="info_table_right_wrap">
						<a href="<?php echo $release['stable']['link1']; ?>" target="_blank">
							<?php echo $release['stable']['link1']; ?></a>
					</div>
					<div id="clear"></div>
				</div>
				<div class="info_table_wrap">
					<div class="info_table_left_wrap"><h4><b>Mirror 1</b></h4></div>
					<div class="info_table_right_wrap">
						<a href="<?php echo $release['stable']['link2']; ?>" target="_blank">
							<?php echo $release['stable']['link2']; ?>
						</a>
					</div>
					<div id="clear"></div>
				</div>
				<div class="info_table_wrap">
					<div class="info_table_left_wrap"><h4><b>Mirror 2</b></h4></div>
					<div class="info_table_right_wrap">
						<a href="<?php echo $release['stable']['link3']; ?>" target="_blank">
							<?php echo $release['stable']['link3']; ?>
						</a>
					</div>
					<div id="clear"></div>
				</div>
				<h3 class="info_editor_header"><i class="fa fa-link"></i>&nbsp; Portable Download Links</h3>
				<div class="info_table_wrap">
					<div class="info_table_left_wrap"><h4><b>Download Link:</b></h4></div>
					<div class="info_table_right_wrap">
						<a href="<?php echo $release['stable']['link4']; ?>" target="_blank"><?php echo $release['stable']['link4']; ?></a>
					</div>
					<div id="clear"></div>
				</div>
				<div class="info_table_wrap">
					<a href="#edit"
					   data-href="edit"
					   data-load-page="adminpanel.edit"
					   data-get-req="view=edit"
					   onclick="force_click_event($(this))"
					   class="btn btn_green">
						<i class="fa fa-pencil"></i>&nbsp;&nbsp; Add a New Version!</a>

					<!-- DO NOT MODIFY ANYTHING! This is enable/disable downloads button -->
					<div id="disable_enable_stable_download_disable">
						<?php if (getAvavilability(0) == 1) : ?>
							<a id="stable_download_disable" data-disable="true" class="btn btn_red" onclick="changeDownload(this.id);">Disable
								Downloads</a>
						<?php else: ?>
							<a id="stable_download_disable" data-disable="false" class="btn btn_green" onclick="changeDownload(this.id);">Enable
								Downloads</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="content_inner_wrapper_admin">
			<div class="admin_margin_wrapper beta_bg_color">
				<div class="infocard_header darkred_color">
					<h3>MusicBee <b>Beta Release</b> Info</h3>
				</div>

				<div class="info_table_wrap">
					<div class="info_table_left_wrap"><h4><b>App Name</b></h4></div>
					<div class="info_table_right_wrap"><?php echo $release['beta']['appname']; ?></div>
					<div id="clear"></div>
				</div>
				<div class="info_table_wrap">
					<div class="info_table_left_wrap"><h4><b>Version</b></h4></div>
					<div class="info_table_right_wrap"><?php echo $release['beta']['version']; ?></div>
					<div id="clear"></div>
				</div>
				<div class="info_table_wrap">
					<div class="info_table_left_wrap"><h4><b>Released on</b></h4></div>
					<div class="info_table_right_wrap"><?php echo $release['beta']['date']; ?></div>
					<div id="clear"></div>
				</div>
				<div class="info_table_wrap">
					<div class="info_table_left_wrap"><h4><b>Supported OS</b></h4></div>
					<div class="info_table_right_wrap"><?php echo $release['beta']['os']; ?></div>
					<div id="clear"></div>
				</div>
				<h3 class="info_editor_header strangeyellow_color"><i class="fa fa-link"></i>&nbsp; Download Links</h3>
				<div class="info_table_wrap">
					<div class="info_table_left_wrap"><h4><b>Download Link:</b></h4></div>
					<div class="info_table_right_wrap">
						<a href="<?php echo $release['beta']['link1']; ?>" target="_blank">
							<?php echo $release['beta']['link1']; ?>
						</a>
					</div>
					<div id="clear"></div>
				</div>
				<div class="info_table_wrap">
					<a href="#editbeta"
					   data-href="editbeta"
					   data-load-page="adminpanel.edit"
					   data-get-req="view=editbeta"
					   onclick="force_click_event($(this))"
					   class="btn btn_green">
						<i class="fa fa-pencil"></i>&nbsp;&nbsp; Add a new Version!</a>
					<!-- DO NOT MODIFY ANYTHING! This is enable/disable downloads button -->
					<div id="disable_enable_beta_download_disable">
						<?php if (getAvavilability(1) == 1) : ?>
							<a id="beta_download_disable" data-disable="true" class="btn btn_red" onclick="changeDownload(this.id);">Disable
								Downloads</a>
						<?php else: ?>
							<a id="beta_download_disable" data-disable="false" class="btn btn_green" onclick="changeDownload(this.id);">Enable
								Downloads</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div id="clear"></div>

		<!-- Disable and Enable Downloads -->
		<script type="text/javascript">

			function changeDownload(id) {
				$('#loading_icon').show();
				$.fx.off = false;
				hideNotification(); //hide any message first
				showHideOverlay();
				var disableLabel = "<a id=\"" + id + "\" data-disable=\"true\" class=\"btn btn_red\" onclick=\"changeDownload(this.id);\">Disable Downloads</a>";
				var enableLabel = "<a id=\"" + id + "\" data-disable=\"false\" class=\"btn btn_green\" onclick=\"changeDownload(this.id);\">Enable Downloads</a>";
				$.ajax({
					url: '<?php $_SERVER['DOCUMENT_ROOT']; ?>/includes/admin.tasks.php',
					data: {"change_id": id},
					cache: false,
					type: "POST",
				}).done(function (data) {
					if ($('#' + id).attr('data-disable') == "false") {
						$('#disable_enable_' + id).html(disableLabel);
					} else {
						$('#disable_enable_' + id).html(enableLabel);
					}
					notificationCallback(data);
				}).fail(function (jqXHR, textStatus, errorThrown) {
					showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "red_color");
				}).always(function () {
					$('#loading_icon').hide('fast');
					showHideOverlay();
				});
			}

		</script>
	<?php endif; ?>