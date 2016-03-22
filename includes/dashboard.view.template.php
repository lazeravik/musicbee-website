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
//Get all the info about the user at the begining


$stat['top_voted_addon'] = $dashboard->getTopVotedAddonsByAuthor ($_SESSION['memberinfo']['memberid'],
                                                                  10);
$stat['top_downloaded_addon'] = $dashboard->getMostDownloadedAddonsByAuthor ($_SESSION['memberinfo']['memberid'],
                                                                             10);

$stat['total_unapproved_addon'] = $dashboard->getAllAddonByStatusAndMember ($_SESSION['memberinfo']['memberid'],
                                                                            0);
$stat['unapproved_addons'] = $dashboard->getAllUnApprovedAddons (10);

?>

<div
	class="main_content_wrapper col_1_2">
	<div class="sub_content_wrapper">
		<div class="box_content">
			<span
				class="show_info custom">
				<h3>
					<i class="fa fa-area-chart"></i>&nbsp;&nbsp;
					Your Stats</h3>
			</span>
			<table class="record">
				<tbody>
				<tr>
					<td>
						<a href="#all"
						   data-href="all"
						   data-load-page="dashboard.all"><?php echo $lang['130']; ?></a>
					</td>
					<td>
						<?php echo Format::number_format_suffix (count ($stat['total_addon_submitted'])); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['132']; ?>
					</td>
					<td>
						<?php echo Format::number_format_suffix (count ($stat['total_unapproved_addon'])); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $lang['131']; ?>
					</td>
					<td>
						<?php echo Format::number_format_suffix ($stat['total_likes']); ?>
					</td>
				</tr>
				<tr>
					<td>
						Total Downloads
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
				<h3><?php echo $lang['137']; ?></h3>
			</span>
			<ul class="link_list">
				<li>
					<a href="">
						<?php echo $lang['139']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['140']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['141']; ?>
					</a>
				</li>
				<li>
					<hr class="line"/>
				</li>
				<li>
					<a href="#submit"
					   data-href="submit"
					   data-load-page="dashboard.submit">
						<?php echo $lang['142']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['143']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['144']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['145']; ?>
					</a>
				</li>
				<li>
					<a href="">
						<?php echo $lang['146']; ?>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="sub_content_wrapper">
		<?php if ($context['user']['can_mod']): ?>
			<div class="box_content">
				<span
					class="show_info info_darkgrey custom header">
					<h3>
						<i class="fa fa-shield"></i>&nbsp;&nbsp;
						Addons waiting
						for approval
					</h3>
					<a href="http://localhost/download/"
					   class="btn small_btn btn_yellow"><i
							class="fa fa-navicon"></i>&nbsp;
						Show All</a>
				</span>
				<p class="show_info warning">
					You are seeing this
					because you are
					either a mod or
					admin
				</p>
				<?php if (!empty($stat['unapproved_addons'])): ?>

					<table
						class="record">
						<thead>
						<tr>
							<td>
								<?php echo $lang['229']; ?>
							</td>
							<td>
								<?php echo $lang['230']; ?>
							</td>
							<td>
								Member
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
										id="addon_reject"
										action="../includes/dashboard.tasks.php"
										method="post"
										data-autosubmit>
										<button
											class="btn btn_red"
											type="submit"
											onclick="addonReject()">
											Reject
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
											Approve
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
				<h3><?php echo $lang['133']; ?></h3>
			</span>
			<?php if (!empty($stat['top_voted_addon'])): ?>
				<table class="record">
					<thead>
					<tr>
						<td>
							<?php echo $lang['229']; ?>
						</td>
						<td>
							<?php echo $lang['230']; ?>
						</td>
						<td>
							Likes
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
				<h3><?php echo $lang['135']; ?></h3>
			</span>
			<?php if (!empty($stat['top_downloaded_addon'])): ?>
				<table class="record">
					<thead>
					<tr>
						<td>
							<?php echo $lang['229']; ?>
						</td>
						<td>
							Downloads
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
		loadPageGet(generateUrl($(this).attr('data-load-page')), (!!$(this).attr('data-get-req')) ? $(this).attr('data-get-req') : "");
		window.location.hash = $(this).attr('data-href');
	});

	function addonApprove() {
		$('form[data-autosubmit][id=addon_approve]').autosubmit();
	}

	function addonReject() {
		$('form[data-autosubmit][id=addon_reject]').autosubmit();
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

	var ajax_reload_page = function () {
		$dataUrl = $('a[href="#overview"]');
		$generatedUrl = generateUrl($dataUrl.attr('data-load-page'));
		loadPageGet($generatedUrl, (!!$dataUrl.attr('data-get-req')) ? $dataUrl.attr('data-get-req') : "");
	}
</script>