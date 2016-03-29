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

include $siteRoot . 'classes/Dashboard.php';
include $siteRoot . 'classes/Stats.php';

$Stats = new Stats();
$dashboard = new Dashboard();

$stat['total_download'] = $Stats->getAddonDownloadCountByAuthor ($_SESSION['memberinfo']['memberid']);
$stat['total_likes'] = $Stats->getAddonLikeCountByAuthor ($_SESSION['memberinfo']['memberid']);
$stat['total_addon_submitted'] = $dashboard->getAllAddonByMember ($_SESSION['memberinfo']['memberid']);
$stat['total_unapproved_addon'] = $dashboard->getAllAddonCountByStatusAndMember ($_SESSION['memberinfo']['memberid'],0);
$stat['top_voted_addon'] = $dashboard->getTopVotedAddonsByAuthor ($_SESSION['memberinfo']['memberid'],10);
$stat['top_downloaded_addon'] = $dashboard->getMostDownloadedAddonsByAuthor ($_SESSION['memberinfo']['memberid'],10);
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
						<?php echo Format::number_format_suffix (count ($stat['total_addon_submitted'])); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['dashboard_2']; ?>
					</td>
					<td>
						<?php echo Format::number_format_suffix ($stat['total_unapproved_addon']); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['dashboard_1']; ?>
					</td>
					<td>
						<?php echo Format::number_format_suffix ($stat['total_likes']); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['dashboard_3']; ?>
					</td>
					<td>
						<?php echo Format::number_format_suffix ($stat['total_download']); ?>
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
				<li>
					<a href="">
						<?php echo $lang['dashboard_links_1']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['dashboard_links_2']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['dashboard_links_3']; ?>
					</a>
				</li>
				<li>
					<hr class="line"/>
				</li>
				<li>
					<a href="#submit"
					   data-href="submit"
					   data-load-page="dashboard.submit">
						<?php echo $lang['dashboard_links_4']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['dashboard_links_5']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['dashboard_links_6']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['dashboard_links_7']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['dashboard_links_8']; ?>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="sub_content_wrapper"
	     id="addon_list_fullview">
		<?php if ($context['user']['can_mod']): ?>
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
									<a href="<?php echo $link['addon']['home'] . $addon['ID_ADDON'] . "/" . Format::Slug ($addon['addon_title']); ?>"
									   target="_blank"
									   title="View this addon"><?php echo $addon['addon_title']; ?></a>
								</td>
								<td>
									<?php echo Format::UnslugTxt ($addon['addon_type']); ?>
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
											<?php echo $lang['dashboard_submit_btn_7']; ?>
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
										action="../includes/dashboard.tasks.php"
										method="post"
										data-autosubmit>
										<button
											class="btn btn_yellow"
											type="submit"
											onclick="addonReject()">
											<?php echo $lang['dashboard_submit_btn_6']; ?>
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
										action="../includes/dashboard.tasks.php"
										method="post"
										data-autosubmit>
										<button
											class="btn btn_blue"
											type="submit"
											onclick="addonApprove()">
											<?php echo $lang['dashboard_submit_btn_5']; ?>
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
			<span
				class="show_info custom">
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
								<a href="<?php echo $link['addon']['home'] . $addon['ID_ADDON'] . "/" . Format::Slug ($addon['addon_title']); ?>"
								   target="_blank"><?php echo $addon['addon_title']; ?></a>
							</td>
							<td>
								<?php echo $addon['addon_type']; ?>
							</td>
							<td>
								<?php echo Format::number_format_suffix ($addon['likesCount']); ?>
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
			<span
				class="show_info custom">
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
								<a href="<?php echo $link['addon']['home'] . $addon['ID_ADDON'] . "/" . Format::Slug ($addon['addon_title']); ?>"
								   target="_blank"><?php echo $addon['addon_title']; ?></a>
							</td>
							<td>
								<?php echo Format::number_format_suffix ($addon['downloadCount']); ?>
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
		/* Act on the event */
		loadPageGet(generateUrl($(this).attr('data-href')), (!!$(this).attr('data-get-req')) ? $(this).attr('data-get-req') : "");
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

	(function ($) {
		$.fn.autosubmit = function () {
			this.submit(function (event) {
				event.preventDefault();
				event.stopImmediatePropagation(); //This will stop the form submit twice
				$('#loading_icon').show(); //show loading icon'
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
			showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "error", "red_color");
		}).always(function () {
			$('#loading_icon').hide(); //show loading icon'
		});
	}

</script>