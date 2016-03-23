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

$ALL_MBVERSION_DASHBOARD = getVersionInfo (0,
                                           "byAllReleases");

//check if edit GET request is made, if not we don't want UNDEFINED ERROR to pop up! so define the variable
if (isset($_GET['view'])) {
	if ($_GET['view'] == "update" && isset($_GET['id'])) {
		$viewType = 2; //update mode
		include './dashboard.tasks.php';
		require_once $siteRoot . 'classes/Addon.php';
		$addon = new Addon(); //create an instance of the addondashboard class
		$data = $addon->getAddonInfo ($_GET['id'])[0];

		$screenshot_array = explode (",",
		                             $data['image_links']);

	}
} else {
	$viewType = 0; //update mode
}
?>
<form action="../includes/dashboard.tasks.php"
      method="post" data-autosubmit>

	<div class="main_content_wrapper col_2_1">
		<div class="sub_content_wrapper">
			<div class="box_content">
			<span class="show_info custom">
				<h3>
					<?php if ($viewType == 2) {
						echo $lang['dashboard_submit_2'];
					} else {
						echo $lang['dashboard_submit_1'];
					} ?></h3>
				<p class="description"><?php if ($viewType == 2) {
						echo $lang['dashboard_submit_desc_2'];
					} else {
						echo $lang['dashboard_submit_desc_1'];
					} ?></p>
			</span>
			</div>
			<div class="box_content">
			<span class="show_info custom info_silver">
				<h3><?php echo $lang['dashboard_submit_header_1']; ?></h3>
			</span>
				<ul class="form">
					<li>
						<label for="type">
							<p>Addon
								type
								*</p>
						</label>
						<select name="type"
						        id="type">
							<?php
							foreach ($main_menu['add-ons']['sub_menu'] as $key => $menu_addon) {
								$type_selection_text = "";
								if (isset($data)) {
									if ($data['addon_type'] == Format::Slug ($menu_addon['title'])) {
										$type_selection_text = "selected";
									}
								}


								echo "<option value=\"" . Format::Slug ($menu_addon['title']) . "\" " . $type_selection_text . ">" . $menu_addon['title'] . "</option>";
							}
							?>
						</select>

					</li>
				</ul>
				<script type="text/javascript">
					$(document).ready(function () {
						showSpecial();
					});
					$('#type').on('change', function () {
						showSpecial();
						console.log($('#type').val());
					});
					function showSpecial() {
						if ($('#type').val() == "skins") {
							$('#addon_special').removeAttr('style');
							$('#addon_special_cover').removeAttr('style');
						}
						else {
							$('#addon_special').css('display', 'none');
							$('#addon_special_cover').css('display', 'none');
						}
					}
				</script>
			</div>

			<div class="box_content">
			<span class="show_info custom info_silver">
				<h3><?php echo $lang['dashboard_submit_header_2']; ?></h3>
			</span>
				<ul class="form">
					<li>
						<label for="title">
							<p><?php echo $lang['dashboard_submit_header_3']; ?></p>
						</label>
						<input type="text"
						       id="title"
						       maxlength="80"
						       name="title"
						       required="required"
						       value="<?php if ($viewType == 2) {
							       echo $data['addon_title'];
						       } ?>"/>

					</li>
					<li>
						<label for="description">
							<p><?php echo $lang['dashboard_submit_header_4']; ?></p>
							<p class="description"><?php echo $lang['dashboard_submit_desc_3']; ?></p>
						</label>
				<textarea type="text"
				          id="description"
				          maxlength="600"
				          name="description"
				          required="required"
				          style="width:60%; min-height:100px;"
				          onkeyup="$('#description_count').text(600 - this.value.length+'/600')"><?php if ($viewType == 2) {
						echo $data['short_description'];
					} ?></textarea>
						<p id="description_count"
						   style="text-align:right"></p>

					</li>
					<li>
						<label for="mbSupportedVer">
							<p><?php echo $lang['dashboard_submit_header_7']; ?></p>
						</label>
						<input id="mbSupportedVer"
						       name="mbSupportedVer"
						       type="hidden"
						       value=""/>
						<select id="multipleVer"
						        multiple="multiple"
						        on>
							<?php
							foreach ($ALL_MBVERSION_DASHBOARD as $ver) {
								if ($ver['dashboard_availablity'] == 1) {
									if ($ver['ID_ALLVERSIONS'] == $data['supported_mbversion']) {
										$selected_mb_text = "selected";
									} else {
										$selected_mb_text = ""; //if not matched then remove the selected text
									}
									echo '<option value="' . $ver['ID_ALLVERSIONS'] . '" ' . $selected_mb_text . '>' . $ver['appname'] . '</option>';
								}
							}
							?>
						</select>
						<p id="mbVerFeedback"
						   class="fadeInUp animated show_info info_green"
						   style="display:none"></p>
					</li>
					<li>
						<hr class="line">
					</li>
					<li>
						<label for="addonver">
							<p><?php echo $lang['dashboard_submit_header_8']; ?></p>
							<p class="description"><?php echo $lang['dashboard_submit_desc_7']; ?></p>
						</label>
						<input id="addonver"
						       value
						       step="0.1"
						       min="1.0"
						       max="99.99"
						       name="addonver"
						       type="text/number"
						       placeholder="eg. 1.0"
						       value="<?php if ($viewType == 2) {
							       echo $data['addon_version'];
						       } ?>"/>

					</li>
					<li>
						<label for="tag">
							<p><?php echo $lang['dashboard_submit_header_9']; ?></p>
						</label>
						<input id="tag"
						       maxlength="150"
						       name="tag"
						       type="text"
						       placeholder="eg. metro, modern, elegant"
						       value="<?php if ($viewType == 2) {
							       echo $data['tags'];
						       } ?>"/>

					</li>
					<li id="addon_special"
					    style="display:none;">
						<label for="color">
							<p>Skin
								accent
								color</p>
							<?php

							foreach ($color_codes as $key => $color):
								$color_selection_text = "";
								if ($viewType == 2) {
									if (Format::Slug ($data['COLOR_ID']) == Format::Slug ($color['name'])) {
										$color_selection_text = "checked";
									}
								} ?>
								<input type="radio"
								       name="color"
								       id="color"
								       value="<?php echo Format::Slug ($color['name']); ?>"
								       style="background:<?php echo $color['value']; ?>" <?php echo $color_selection_text; ?>>
							<?php endforeach; ?>
						</label>
					</li>
				</ul>
			</div>
			<div class="box_content">
			<span class="show_info custom">
				<h3><?php echo $lang['dashboard_submit_header_5']; ?></h3>
			</span>
				<ul class="form">
					<li>
						<label for="dlink">
							<p><?php echo $lang['dashboard_submit_header_10']; ?></p>
						</label>
						<input type="url"
						       id="dlink"
						       name="dlink"
						       required="required"
						       value="<?php if ($viewType == 2) {
							       echo $data['download_links'];
						       } ?>"/>

					</li>
					<li>
						<label for="thumb">
							<p><?php echo $lang['dashboard_submit_header_11']; ?></p>
						</label>
						<div class="link_input">
							<div class="flex_input col_2">
								<input id="thumb"
								       type="text"
								       name="thumb"
								       value="<?php if ($viewType == 2) {
									       echo $data['thumbnail'];
								       } ?>"
								       placeholder="eg. http://i.imgur.com/sdfsdf43gh5.jpg"/>
								<button
										type="button"
										onclick="showUpModal('thumb','img')"
										id="upload_to_imgur"
										class="btn btn_green input_add"
										title="<?php echo $lang['dashboard_tooltip_3']; ?>">
									<i class="fa fa-upload"></i>
								</button>
							</div>
						</div>
					</li>
					<li class="screenshot_multiple_wrap">
						<label for="imglink">
							<p><?php echo $lang['dashboard_submit_header_12']; ?></p>
							<p class="description"><?php echo $lang['dashboard_submit_desc_5']; ?></p>
						</label>

						<?php if ($viewType == 2) : ?>
							<div id="screenshot_inputs"
							     class="link_input">
								<?php
								foreach ($screenshot_array as $key => $screenshots):
									$rand_container_id = uniqid ();
									?>
									<div class="flex_input col_2">
										<input id="<?php echo $rand_container_id; ?>"
										       type="text"
										       name="screenshot_links[]"
										       value="<?php echo $screenshots; ?>"
										       placeholder="eg. http://i.imgur.com/<?php echo $rand_container_id; ?>.jpg"/>
										<button
												type="button"
												onclick="showUpModal('<?php echo $rand_container_id; ?>','img')"
												id="upload_to_imgur"
												class="btn btn_green"
												title="<?php echo $lang['dashboard_tooltip_3']; ?>">
											<?php echo $lang['dashboard_submit_btn_1']; ?>
										</button>
										<?php if ($key != 0): ?>
											<a href="javascript:void(0)" id="remove_button" class="btn remove_img_btn"><?php echo $lang['dashboard_submit_btn_3'].$lang['dashboard_submit_btn_4']; ?></a>
										<?php endif; ?>
									</div>

								<?php endforeach; ?>
							</div>
							<button
									type="button"
									id="add_button"
									class="btn btn_blue">
								<?php echo $lang['dashboard_submit_btn_2']; ?>
							</button>
						<?php else: ?>
							<div id="screenshot_inputs"
							     class="link_input">
								<div class="flex_input col_2">
									<input id="vfda54huk"
									       type="text"
									       name="screenshot_links[]"
									       value=""
									       placeholder="eg. http://i.imgur.com/vfda54huk.jpg"/>

									<button
											type="button"
											onclick="showUpModal('vfda54huk','img')"
											id="upload_to_imgur"
											class="btn btn_green"
											title="<?php echo $lang['dashboard_tooltip_3']; ?>">
										<?php echo $lang['dashboard_submit_btn_1']; ?>
									</button>
								</div>
							</div>
							<button
									type="button"
									id="add_button"
									class="btn btn_blue"
									title="<?php echo $lang['dashboard_tooltip_3']; ?>">
								<?php echo $lang['dashboard_submit_btn_2']; ?>
							</button>
						<?php endif; ?>
					</li>
				</ul>
			</div>
			<div class="box_content">
			<span class="show_info custom info_silver">
				<h3><?php echo $lang['dashboard_submit_header_6']; ?></h3>
			</span>
				<ul class="form">
					<li>
						<label for="support">
							<p><?php echo $lang['dashboard_submit_header_13']; ?></p>
							<p class="description"><?php echo $lang['dashboard_submit_desc_6']; ?></p>
						</label>
						<input type="url"
						       id="support"
						       name="support"
						       value="<?php if ($viewType == 2) {
							       echo $data['support_forum'];
						       } ?>"/>

					</li>
					<li>
						<hr class="line">
					</li>
					<li>
						<label for="wmd-input">
							<p><?php echo $lang['dashboard_submit_header_14']; ?></p>
						</label>
						<div id="wmd-editor"
						     class="wmd-panel">
							<div id="wmd-button-bar"></div>
								<textarea
										id="wmd-input"
										name="readme"
										onkeyup="$('#wmd-input_count').text(5000 - this.value.length+'/5000')"><?php if ($viewType == 2) {
										echo $data['readme_content'];
									} ?></textarea>
						</div>
						<p id="wmd-input_count"
						   style="text-align:right"></p>

						<div id="wmd-preview"
						     class="wmd-panel markdownView"></div>
					</li>
				</ul>
			</div>
			<div class="box_content">
				<ul class="form">
					<li>
						<label for="imp_msg">
							<p><?php echo $lang['dashboard_submit_header_15']; ?></p>
							<p class="description">
								<?php echo $lang['dashboard_submit_desc_7']; ?>
							</p>
						</label>
							<textarea
									id="imp_msg"
									name="important_note"
									maxlength="200"
									style="width:60%; min-height:100px;"
									onkeyup="$('#imp_msg_count').text(200 - this.value.length+'/200')"><?php if ($viewType == 2) {
									echo $data['important_note'];
								} ?></textarea>
						<p id="imp_msg_count"
						   style="text-align:right"></p>

					</li>
				</ul>
			</div>
			<div class="box_content">
				<ul class="form">
					<li>
						<button class="btn btn_blue"
						        type="submit"
						        id="submit"
						        style="padding:15px"
						        onclick="saveEdit()"><?php echo $lang['home_30']; ?></button>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<?php if ($viewType == 2): ?>
		<input type="hidden"
		       name="modify_type"
		       value="update"/>
		<input type="hidden"
		       name="record_id"
		       value="<?php echo $data['ID_ADDON']; ?>"/>
	<?php else: ?>
		<input type="hidden"
		       name="submit"
		       value="true"/>
	<?php endif; ?>
</form>
<div class="space medium"></div>
<div id="upView"
     class="modalBox iw-modalBox fadeIn animated"></div>

<script type="text/javascript">
	//generate random id for image input field
	function randId() {
		return Math.random().toString(36).substring(7);
	}

	//show imgur upload modal box
	function showUpModal(id, upType) {
		$fromTop = $(window).scrollTop(),
				$('.modalBox').modalBox({
					width: '680',
					height: '400',
					top: 'auto',
					left: 'auto',
					keyClose: true,
					iconClose: true,
					bodyClose: true,
					<?php if($viewType == 2): ?>
					overlay: false,
					<?php else: ?>
					overlay: true,
					<?php endif; ?>
					onOpen: function () {
						$('#blur_content_id').addClass('blur_content_overlay');
						$('#upView').html("<div class=\"sk-circle\"> <div class=\"sk-circle1 sk-child\"></div> <div class=\"sk-circle2 sk-child\"></div> <div class=\"sk-circle3 sk-child\"></div> <div class=\"sk-circle4 sk-child\"></div> <div class=\"sk-circle5 sk-child\"></div> <div class=\"sk-circle6 sk-child\"></div> <div class=\"sk-circle7 sk-child\"></div> <div class=\"sk-circle8 sk-child\"></div> <div class=\"sk-circle9 sk-child\"></div> <div class=\"sk-circle10 sk-child\"></div> <div class=\"sk-circle11 sk-child\"></div> <div class=\"sk-circle12 sk-child\"></div> </div>"); //show loading signal maybe!
						if (upType == "img") {
							loadImgurUpload(id);
						} else if (upType == "file") {
							loadMediafireUpload(id);
						}

					},
					onClose: function () {
						<?php if($viewType == 2): ?>
						$('body').css({
							position: 'fixed',
							width: '100%',
							'top': '-' + $(window).scrollTop() + 'px',
							'overflow-x': 'hidden',
							'overflow-y': 'hidden'
						});
						<?php endif; ?>
						$('#blur_content_id').removeClass('blur_content_overlay');
						$('#upView').removeClass('busy');
						$('#upView').html(""); //delete the html we got from ajax req
					}
				});
	}

	function loadImgurUpload(id) {
		$.fx.off = true; // turn off jquery animation effects
		$.ajax({
			url: '../includes/upload.imgur.php',
			cache: false,
			type: "POST",
			data: {id: "" + id + ""}
		}).done(function (data) {
			if ($('#upView').children().length > 0) {
				$('#upView').html(data);
				hideNotification();
			}
		}).fail(function (jqXHR, textStatus, errorThrown) {
			showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "error", "red_color");
		}).always(function () {
		});
	}

	function loadMediafireUpload(id) {
		$.fx.off = true; // turn off jquery animation effects
		$.ajax({
			url: '../includes/upload.mediafire.php',
			cache: false,
			type: "POST",
			data: {id: "" + id + ""}
		}).done(function (data) {
			if ($('#upView').children().length > 0) {
				$('#upView').html(data);
				hideNotification();
			}
		}).fail(function (jqXHR, textStatus, errorThrown) {
			showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "error", "red_color");
		}).always(function () {
		});
	}
	
	$("#multipleVer").change(addVer);
	function addVer() {
		$selectedVer = "";
		$selectedVerId = "";
		$("#multipleVer > option:selected").each(function (index, el) {
			if ($selectedVer == "") {
				$selectedVer = el.text;
				$selectedVerId = el.value;
			} else {
				$selectedVer = $selectedVer + ", " + el.text;
				$selectedVerId = $selectedVerId + "," + el.value;
			}
		});
		$('#mbSupportedVer').val($selectedVerId);
		if ($selectedVer != "") {
			$('#mbVerFeedback').show();
			$('#mbVerFeedback').html('<?php echo $lang['dashboard_msg_3'];?> <b>' + $selectedVer + '</b>');
		} else {
			$('#mbVerFeedback').hide();
		}
	}


	$(function () {
		$('#tag').tagsInput({
			'width': 'auto',
			'height': 'auto',
			'interactive': true,
			'minChars': 2,
			'defaultText': 'add a tag',
			'removeWithBackspace': true,
			'delimiter': [',', ';'],
			'maxChars': 10, // if not provided there is no limit
			'placeholderColor': '#333'
		});
	});

	$(document).ready(function () {
		<?php
		//for updating purpose a musicbee version should be always selected, so check it on load
		if ($viewType == 2): ?>
		addVer();
		<?php endif; ?>

		MBEditor.wmdBase();
		MBEditor.Util.startEditor();

		var maxField = 8; //Input fields increment limitation
		var wrapper = $('#screenshot_inputs'); //Input field wrapper
		$('#add_button').click(function () { //Once add button is clicked
			var randomId = randId();
			var randPlaceholder = "http://i.imgur.com/" + randomId;
			var fieldHTML = '<div class="flex_input col_2">' +
					'<input id="' + randomId + '" type="text" name="screenshot_links[]" value="" placeholder="eg. ' + randPlaceholder + '.jpg" required/>' +
					'<button onclick="showUpModal(\'' + randomId + '\',\'img\')" id="upload_to_imgur" class="btn btn_green" title="<?php echo $lang['dashboard_tooltip_3']; ?>"><?php echo $lang['dashboard_submit_btn_1']; ?></button>' +
					'<a href="javascript:void(0)" id="remove_button" class="btn remove_img_btn"><?php echo $lang['dashboard_submit_btn_3'].$lang['dashboard_submit_btn_4']; ?></a>' +
					'</div>';
			if (wrapper.children().length < maxField) { //Check maximum number of input fields
				$(wrapper).append(fieldHTML); // Add field html
			}
		});
		$(wrapper).on('click', '#remove_button', function (e) { //Once remove button is clicked
			e.preventDefault();
			$(this).parent('div').remove(); //Remove field html
		});
	});


	function saveEdit() {
		$('form[data-autosubmit]').autosubmit();
	}

	(function ($) {
		$.fn.autosubmit = function () {
			this.submit(function (event) {
				event.preventDefault();
				event.stopImmediatePropagation(); //This will stop the form submit twice
				var form = $(this);
				hideNotification();
				showOverlay();
				$('#loading_icon').show();
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
					hideOverlay();//show overlay while loading
				});
			});
		}
		return false;
	})(jQuery)

	<?php if ($viewType == 2): ?>
	function submitted() {
		var $generatedUrl = generatePageUrl((window.location.hash).replace('#', ''));
		loadPageGet($generatedUrl);
	}
	<?php else: ?>
	function submitted() {
		var $generatedUrl = generatePageUrl((window.location.hash).replace('#', ''));
		loadPageGet($generatedUrl);
	}
	<?php endif; ?>
</script>