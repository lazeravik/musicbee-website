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

$no_guests = true; //kick off the guests
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/functions.php';

include $link['root'] . 'classes/Dashboard.php';

$dashboard = new Dashboard();

$stat['total_download'] = $dashboard->getAddonDownloadCount ($mb['user']['id']);
$stat['total_likes'] = $dashboard->getAddonLikeCount ($mb['user']['id']);
$stat['total_addon_submitted'] = $dashboard->getAllAddonByMember ($mb['user']['id']);
$stat['total_unapproved_addon'] = $dashboard->getAllAddonCountByStatusAndMember ($mb['user']['id'],0);
$stat['top_voted_addon'] = $dashboard->getTopVotedAddonsByAuthor ($mb['user']['id'],10);
$stat['top_downloaded_addon'] = $dashboard->getMostDownloadedAddonsByAuthor ($mb['user']['id'],10);
$stat['unapproved_addons'] = array_slice ($dashboard->getAllUnApprovedAddons (),
                                          0,
                                          10);


?>

<div
	class="main_content_wrapper col_1_2">
	<div class="sub_content_wrapper">
		<div class="box_content">
			<span
				class="show_info custom">
				<h3><?php echo $lang['dashboard_5']; ?></h3>
			</span>
			<table class="record">
				<tbody>
				<tr>
					<td>
						<a href="#dashboard_all" data-href="dashboard_all"><?php echo $lang['dashboard_0']; ?></a>
					</td>
					<td>
						<?php echo Format::numberFormatSuffix (count ($stat['total_addon_submitted'])); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['dashboard_2']; ?>
					</td>
					<td>
						<?php echo Format::numberFormatSuffix ($stat['total_unapproved_addon']); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['dashboard_1']; ?>
					</td>
					<td>
						<?php echo Format::numberFormatSuffix ($stat['total_likes']); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['dashboard_3']; ?>
					</td>
					<td>
						<?php echo Format::numberFormatSuffix ($stat['total_download']); ?>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<div
			class="box_content side_links">
			<span
				class="show_info info_green custom">
				<h3><?php echo $lang['dashboard_4']; ?></h3>
			</span>
			<ul class="link_list">
				<?php
				if($mb['help']['help_links']['data'] != null) {
					$dashboard_help_links = json_decode($mb['help']['help_links']['data'])->dashboard;

					if ($dashboard_help_links != null):
						foreach ($dashboard_help_links as $help_link): ?>

							<li>
								<a href="<?php echo $help_link->url; ?>"
								   target="<?php echo isset($help_link->target) ? $help_link->target : ''; ?>"><?php echo $help_link->name; ?></a>
							</li>

						<?php endforeach;
					endif;
				}?>
				<hr class="line"/>

				<li>
					<a href="<?php echo $link['api']; ?>" target="_blank">
						<?php echo $lang['dashboard_links_1']; ?>
					</a>
				</li>
				<li>
					<a href="<?php echo $link['bugreport']; ?>" target="_blank">
						<b><?php echo $lang['dashboard_links_5']; ?></b>
					</a>
				</li>

			</ul>
		</div>
	</div>
	<div class="sub_content_wrapper"
	     id="addon_list_fullview">
		<?php if ($mb['user']['can_mod']): ?>
			<div class="box_content"
			     id="addon_records">
				<span
					class="show_info info_darkgrey custom header">
					<h3><?php echo $lang['dashboard_6']; ?></h3>
				</span>
				<p class="show_info warning">
					<?php echo $lang['dashboard_7']; ?>
				</p>
				<?php if (!empty($stat['unapproved_addons'])): ?>
					<table
						class="record">
						<thead>
						<tr>
							<td>
								<?php echo $lang['dashboard_record_th_1']; ?>
							</td>
							<td>
								<?php echo $lang['dashboard_record_th_2']; ?>
							</td>
							<td>
								<?php echo $lang['dashboard_record_th_3']; ?>
							</td>
							<td>

							</td>
						</tr>
						</thead>
						<tbody>
						<?php foreach ($stat['unapproved_addons'] as $key => $addon): ?>
							<tr>
								<td>
									<a href="<?php echo $link['addon']['home'] . $addon['ID_ADDON'] . "/" . Format::slug ($addon['addon_title']); ?>"
									   target="_blank"
									   title="View this addon"><?php echo $addon['addon_title']; ?></a>
								</td>
								<td>
									<?php echo $mb['main_menu']['add-ons']['sub_menu'][$addon['category']]['title']; ?>
								</td>
								<td>
									<a href="<?php echo $link['forum']; ?>?action=profile;u=<?php echo $addon['ID_MEMBER']; ?>"
									   target="_blank"
									   title="Go to member forum profile"><?php echo $addon['membername']; ?></a>
								</td>
								<td class="action_input">
									<form
											id="addon_delete"
											action="../includes/dashboard.tasks.php"
											method="post"
											data-autosubmit>
										<button
												class="btn btn_red"
												type="submit"
												onclick="addonDelete()">
											<?php echo $lang['delete_icon']; ?>
										</button>
										<input
												type="hidden"
												name="record_id"
												value="<?php echo $addon['ID_ADDON']; ?>"/>
										<input
												type="hidden"
												name="modify_type"
												value="soft_delete"/>
									</form>

									<form
										id="addon_reject"
										action="<?php echo $link['app-url']; ?>includes/dashboard.tasks.php"
										method="post"
										data-autosubmit>
										<button
											class="btn btn_yellow"
											type="submit"
											onclick="addonReject()">
											<?php echo $lang['reject']; ?>
										</button>
										<input
											type="hidden"
											name="addon_id"
											value="<?php echo $addon['ID_ADDON']; ?>"/>
										<input
											type="hidden"
											name="addon_approve"
											value="2"/>
									</form>
									<form
										id="addon_approve"
										action="<?php echo $link['app-url']; ?>includes/dashboard.tasks.php"
										method="post"
										data-autosubmit>
										<button
											class="btn btn_blue"
											type="submit"
											onclick="addonApprove()">
											<?php echo $lang['approve']; ?>
										</button>
										<input
											type="hidden"
											name="addon_id"
											value="<?php echo $addon['ID_ADDON']; ?>"/>
										<input
											type="hidden"
											name="addon_approve"
											value="1"/>
									</form>

								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				<?php else: ?>
					<p class="message"><?php echo $lang['dashboard_err_3']; ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="box_content">
			<span class="show_info custom">
				<h3><?php echo $lang['dashboard_8']; ?></h3>
			</span>
			<?php if (!empty($stat['top_voted_addon'])): ?>
				<table class="record">
					<thead>
					<tr>
						<td>
							<?php echo $lang['dashboard_record_th_1']; ?>
						</td>
						<td>
							<?php echo $lang['dashboard_record_th_2']; ?>
						</td>
						<td>
							<?php echo $lang['dashboard_record_th_5']; ?>
						</td>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($stat['top_voted_addon'] as $key => $addon): ?>
						<tr>
							<td>
								<a href="<?php echo $link['addon']['home'] . $addon['ID_ADDON'] . "/" . Format::slug ($addon['addon_title']); ?>"
								   target="_blank"><?php echo $addon['addon_title']; ?></a>
							</td>
							<td>
								<?php echo $mb['main_menu']['add-ons']['sub_menu'][$addon['category']]['title']; ?>
							</td>
							<td>
								<?php echo Format::numberFormatSuffix ($addon['likesCount']); ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			<?php else: ?>
				<p class="message"><?php echo $lang['dashboard_err_2']; ?></p>
			<?php endif; ?>
		</div>
		<div class="box_content">
			<span class="show_info custom">
				<h3><?php echo $lang['dashboard_9']; ?></h3>
			</span>
			<?php if (!empty($stat['top_downloaded_addon'])): ?>
				<table class="record">
					<thead>
					<tr>
						<td>
							<?php echo $lang['dashboard_record_th_1']; ?>
						</td>
						<td>
							<?php echo $lang['dashboard_record_th_6']; ?>
						</td>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($stat['top_downloaded_addon'] as $key => $addon): ?>
						<tr>
							<td>
								<a href="<?php echo $link['addon']['home'] . $addon['ID_ADDON'] . "/" . Format::slug ($addon['addon_title']); ?>"
								   target="_blank"><?php echo $addon['addon_title']; ?></a>
							</td>
							<td>
								<?php echo Format::numberFormatSuffix ($addon['downloadCount']); ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			<?php else: ?>
				<p class="message"><?php echo $lang['dashboard_err_2']; ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>

<div class="space medium"></div>

<script type="text/javascript">
	//sidebar link redirect and ajax load
	$('#ajax_area a[data-load-page]').on('click', function (e) {
		e.preventDefault();
		window.location.hash = $(this).attr('data-href');
	});

	function addonApprove() {
		$('form[data-autosubmit][id=addon_approve]').autosubmit();
	}

	function addonDelete() {
		$('form[data-autosubmit][id=addon_delete]').autosubmit();
	}

	function addonReject() {
		$('form[data-autosubmit][id=addon_reject]').autosubmit();
	}


	var remove_addon_record = function() {
		reload_addon_approval_list_overview();
	}

	var reload_addon_approval_list_overview = function () {
		var $generatedUrl = generatePageUrl((window.location.hash).replace('#', ''));
		$('#loading_icon').show(); //show loading icon'
		$.ajax({
			url: $generatedUrl,
			cache: false,
			type: "POST",
		}).done(function (data) {
			var sourcedata = $('#addon_records > *', $(data));
			$('#addon_records').html(sourcedata).fadeIn();
		}).fail(function (jqXHR, textStatus, errorThrown) {
			showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "red_color");
		}).always(function () {
			$('#loading_icon').hide(); //show loading icon'
		});
	}

</script>
