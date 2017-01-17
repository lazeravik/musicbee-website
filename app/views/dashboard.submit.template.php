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
require_once $_SERVER['DOCUMENT_ROOT'].'/app/functions.php';
require_once $link['root'].'classes/Dashboard.php';
require_once $link['root'].'classes/Addon.php';

//check if edit GET request is made, if not we don't want UNDEFINED ERROR to pop up! so define the variable
if(isset($_GET['view']))
{
	if($_GET['view'] == "update" && isset($_GET['id']))
	{
		$dashboard = new Dashboard();
		$is_author = $dashboard->verifyAuthor($user_info['id'], $_GET['id']);

		//verify if the author can modify it.... or if user is mod/admin allow
		if(!$is_author && !$mb['user']['can_mod']) { ?>
			<div class="main_content_wrapper col_2_1">
				<div class="sub_content_wrapper">
					<div class="box_content">
						<span class="show_info info_red custom">
							<h3><?php echo $lang['dashboard_err_18']; ?></h3>
						</span>
						<p class="info_text">
							<?php echo $lang['dashboard_err_12']; ?>
						</p>
					</div>
				</div>
			</div>
			<?php exit();
		}


		$viewType = 2; //update mode

		$addon = new Addon(); //create an instance of the addondashboard class
		$data = $addon->getAddonData($_GET['id']);
		$screenshot_array = $data['image_links'];

	} else {
		$viewType = 0; //submit mode
	}
} else {
	$viewType = 0; //submit mode
}


/**
 * If Dashboard submission is turned off show error message
 */
if($viewType == 0 && !$setting['addonSubmissionOn']) { ?>
	<div class="main_content_wrapper col_2_1">
		<div class="sub_content_wrapper">
			<div class="box_content">
				<div class="show_info info_red custom">
					<h3><?php echo $lang['dashboard_err_20']; ?></h3>
					<p class="description"><?php echo $lang['dashboard_err_21']; ?></p>
				</div>
			</div>
		</div>
	</div>

	<?php
	exit();
}

/**
 * If the request is for addon submission success the show a success diologue
 */

if(isset($_GET['action'])):
	if($_GET['action'] == "submit_success"): ?>
		<div class="main_content_wrapper col_2_1">
			<div class="sub_content_wrapper">
				<div class="box_content">
			<div class="show_info info_green custom">
				<h3><?php echo $lang['dashboard_msg_11']; ?></h3>
			</div>
					<?php if($mb['user']['need_approval']): ?>
						<p class="info_text">
							<?php echo $lang['dashboard_msg_5']; ?>
						</p>
					<?php else: ?>
						<p class="info_text">
							<?php echo $lang['dashboard_msg_6']; ?>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<?php
		exit();
	endif;
endif; ?>


<?php if($viewType == 2 && $data['status'] == 3 && !$mb['user']['can_mod']) { ?>
	<div class="main_content_wrapper col_2_1">
		<div class="sub_content_wrapper">
			<div class="box_content">
			<span class="show_info info_red custom">
				<h3><?php echo $lang['dashboard_err_18']; ?></h3>
			</span>
				<p class="info_text">
					<?php echo $lang['dashboard_msg_9']; ?>
				</p>
			</div>
		</div>
	</div>
	<?php
	exit();
}
?>

<form action="<?php echo $link['app-url']; ?>includes/dashboard.tasks.php" method="post" id="addon_submission" data-autosubmit>
	<div class="main_content_wrapper col_2_1">
		<div class="sub_content_wrapper">
			<div class="box_content">
			<span class="show_info custom">
				<h3>
					<?php if($viewType == 2) {
						echo $lang['dashboard_submit_2'];
					} else {
						echo $lang['dashboard_submit_1'];
					} ?>
				</h3>
				<p class="description"><?php if($viewType == 2) {
						echo $lang['dashboard_submit_desc_2'];
					} else {
						echo $lang['dashboard_submit_desc_1'];
					} ?>
				</p>
			</span>
			</div>

			<?php if(isset($_GET['mod'])): ?>
				<div class="box_content">
					<span class="show_info info_red custom">
						<h3><?php echo $lang['dashboard_submit_header_19']; ?></h3>
						<p class="description"><?php echo $lang['dashboard_msg_13']; ?></p>
					</span>
				</div>
			<?php endif; ?>


			<div class="box_content">
			<span class="show_info custom info_silver">
				<h3><?php echo $lang['dashboard_submit_header_1']; ?></h3>
			</span>
				<ul class="form">
					<li>
						<label for="type">
							<p><?php echo $lang['dashboard_submit_header_16']; ?></p>
						</label>
						<select name="type" id="type">
							<?php
							foreach($mb['main_menu']['add-ons']['sub_menu'] as $key => $menu_addon) {
								$type_selection_text = "";
								if(isset($data)) {
									if($data['category'] == $menu_addon['id']) {
										$type_selection_text = "selected";
									}
								}
								echo "<option value=\"".Format::slug($menu_addon['id'])."\" ".$type_selection_text.">".$menu_addon['title']."</option>";
							}
							?>
						</select>
					</li>
				</ul>
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
						       value="<?php if($viewType == 2) {
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
				          onkeyup="$('#description_count').text(600 - this.value.length+'/600')"><?php if($viewType == 2) {
						echo $data['short_description'];
					} ?></textarea>
						<p id="description_count" class="counter"></p>

					</li>
					<li>
						<label for="mbSupportedVer">
							<p><?php echo $lang['dashboard_submit_header_7']; ?></p>
						</label>
						<input id="mbSupportedVer"
						       name="mbSupportedVer"
						       type="hidden"
						       value=""/>
						<select id="multipleVer">
							<?php
							foreach(getVersionInfo(0, "byAllReleases") as $ver) {
								if($ver['dashboard_availablity'] == 1) {
									if($viewType == 2){
										if($ver['ID_ALLVERSIONS'] == $data['supported_mbversion'])
											$selected_mb_text = "selected";
										else
											$selected_mb_text = "";
									} else {
										$selected_mb_text = ""; //if not matched then remove the selected text
									}
									echo '<option value="'.$ver['ID_ALLVERSIONS'].'" '.$selected_mb_text.'>'.$ver['appname'].'</option>';
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
						</label>
						<input id="addonver"
						       name="addonver"
						       type="text/number"
						       placeholder="<?php echo sprintf($lang['input_placeholder_eg'], '1.0'); ?>"
						       value="<?php echo ($viewType == 2) ? $data['addon_version'] : ''; ?>"/>

					</li>
					<li>
						<label for="tag">
							<p><?php echo $lang['dashboard_submit_header_9']; ?></p>
						</label>
						<input id="tag"
						       name="tag"
						       type="text"
						       value="<?php if($viewType == 2) {
							       echo implode(',',$data['tags']);
						       } ?>"/>

					</li>
					<p id="mbTagFeedback"
					   class="fadeInUp animated show_info warning"
					   style="display:none"></p>
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
							<p class="description">
								<?php echo $lang['dashboard_submit_desc_8']; ?>
							</p>
						</label>
						<input type="url"
						       id="dlink"
						       name="dlink"
						       required="required"
						       value="<?php if($viewType == 2) {
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
								       value="<?php if($viewType == 2) {
									       echo $data['thumbnail'];
								       } ?>"
								       placeholder="<?php echo sprintf($lang['input_placeholder_eg'], 'http://i.imgur.com/sdfsdf43gh5.jpg'); ?>"/>
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
					<li>
						<hr class="line">
					</li>
					<li class="screenshot_multiple_wrap">
						<label for="imglink">
							<p><?php echo $lang['dashboard_submit_header_12']; ?></p>
							<p class="description"><?php echo $lang['dashboard_submit_desc_5']; ?></p>
							<button
									type="button"
									id="add_button"
									class="btn btn_blue"
									title="<?php echo $lang['dashboard_tooltip_3']; ?>"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;&nbsp;
								<?php echo $lang['add_more_screenshot']; ?>
							</button>
						</label>

						<?php if($viewType == 2) : ?>
						<div id="screenshot_inputs"
						     class="link_input">
							<?php foreach($screenshot_array as $key => $screenshots):
								$rand_container_id = uniqid(); ?>

								<div class="flex_input col_2">
									<div class="up_group">
										<input id="<?php echo $rand_container_id; ?>"
										       type="text"
										       name="screenshot_links[]"
										       value="<?php echo $screenshots; ?>"
										       placeholder="<?php echo sprintf($lang['input_placeholder_eg'], 'http://i.imgur.com/'.$rand_container_id.'.jpg'); ?>"/>

										<?php if($key != 0): ?>
											<a href="javascript:void(0)"
											   id="remove_button"
											   class="btn remove_img_btn"
											   title="<?php echo $lang['dashboard_submit_btn_4']; ?>"><?php echo $lang['remove_icon']; ?></a>
										<?php endif; ?>
									</div>
									<button
											type="button"
											onclick="showUpModal('<?php echo $rand_container_id; ?>','img')"
											id="upload_to_imgur"
											class="btn btn_green"
											title="<?php echo $lang['dashboard_tooltip_3']; ?>">
										<?php echo $lang['upload_icon']; ?>
									</button>
								</div>

							<?php endforeach; else: ?>
								<div id="screenshot_inputs" class="link_input">
									<div class="flex_input col_2">
										<input id="vfda54huk"
										       type="text"
										       name="screenshot_links[]"
										       value=""
										       placeholder="<?php echo sprintf($lang['input_placeholder_eg'], 'http://i.imgur.com/vfda54huk.jpg'); ?>"/>

										<button
												type="button"
												onclick="showUpModal('vfda54huk','img')"
												id="upload_to_imgur"
												class="btn btn_green"
												title="<?php echo $lang['dashboard_tooltip_3']; ?>">
											<?php echo $lang['upload_icon']; ?>
										</button>
									</div>
								</div>
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
						       value="<?php if($viewType == 2) {
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
						<div id="wmd-editor" class="wmd-panel">
							<div id="wmd-button-bar"></div>
								<textarea
										id="wmd-input"
										name="readme"
										onkeyup="$('#wmd-input_count').text(5000 - this.value.length+'/15000')"><?php if($viewType == 2) {
										echo $data['readme_content'];
									} ?></textarea>
						</div>
						<p id="wmd-input_count" class="counter"></p>
						<div id="wmd-preview" class="wmd-panel markdownView box"></div>
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
									onkeyup="$('#imp_msg_count').text(200 - this.value.length+'/200')"><?php if($viewType == 2) {
									echo $data['important_note'];
								} ?></textarea>
						<p id="imp_msg_count" class="counter"></p>

					</li>
				</ul>
			</div>
			<div class="box_content">
				<ul class="form">
					<li>
						<button class="btn btn_blue"
						        type="submit"
						        onclick="saveEdit()"><?php echo $lang['home_30']; ?></button>
					</li>
				</ul>
			</div>
		</div>
		<div class="sub_content_wrapper">
			<div class="box_content">
				<span class="show_info custom info_silver">
					<h3>
						<?php echo $lang['dashboard_submit_header_17']; ?>
					</h3>
				</span>
				<ul class="form">
					<li>
						<label for="beta">
							<p><?php echo $lang['dashboard_submit_header_18']; ?></p>
							<p class="description"><?php echo $lang['dashboard_submit_desc_9']; ?></p>
						</label>
						<select name="beta" id="beta">
							<option value="0"><?php echo $lang['beta_dropdown_no']; ?></option>
							<option value="1" <?php if($viewType == 2) {
								if($data['is_beta'] == 1) {
									echo 'selected';
								}
							} ?>><?php echo $lang['beta_dropdown_yes']; ?>
							</option>
						</select>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<?php if($viewType == 2): ?>
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
<div id="upView" class="modalBox iw-modalBox fadeIn animated"></div>

<script src="<?php echo GetScriptDir(); ?>multiple-select.js"></script>
<script src="<?php echo GetScriptDir(); ?>jquery.tagsinput.min.js"></script>
<script type="text/javascript">

	//Multiple Musicbee version selection diologue initializer

	$('#multipleVer').multipleSelect({
				placeholder: "Select targetted MusicBee Version",
				selectAll: false,
				single: true,
				width: "none",
			}<?php if ($viewType == 2): ?>,
			"setSelects", [<?php echo implode(',', $data['supported_mbversion_ids']); ?>]
			<?php endif; ?>
	);

	//generate random id for image input field placeholder
	function randId() {
		return Math.random().toString(36).substring(7);
	}

	//show imgur upload modal box
	function showUpModal(id, upType) {
		$('.modalBox').modalBox({
			width: '680',
			height: '347px',
			top: 'auto',
			left: 'auto',
			keyClose: true,
			iconClose: true,
			bodyClose: true,
			onOpen: function () {
				$('#upView').html('<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="5" stroke-miterlimit="10"/></svg></div>'); //show loading signal maybe!
				if (upType == "img") {
					loadImgurUpload(id);
				}
			},
			onClose: function () {
				$('#upView').removeClass('busy');
				$('#upView').html(""); //delete the html we got from ajax req
			}
		});
	}

	//load Imgur upload diologue content via ajax
	function loadImgurUpload(id) {
		$.fx.off = true; // turn off jquery animation effects
		$.ajax({
			url: '<?php echo $link['url']; ?>views/upload.imgur.template.php',
			cache: false,
			type: "POST",
			data: {id: "" + id + ""}
		}).done(function (data) {
			if ($('#upView').children().length > 0) {
				$('#upView').html(data);
				hideNotification();
			}
		}).fail(function (jqXHR, textStatus, errorThrown) {
			showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "red_color");
		}).always(function () {
		});
	}


	 $("#multipleVer").change(addVer);
	 function addVer() {
		 var $selectedVer = "";
		 var $selectedVerId = "";
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

	//Tag Input initializer
	$(function () {
		$('#tag').tagsInput({
			'width': 'none',
			'height': 'auto',
			'interactive': true,
			'minChars': 2,
			'defaultText': '<?php echo $lang['input_placeholder_tag']; ?>',
			'maxChars': 15,
			'onChange': tag_limit_checker
		});
	});

	//Check if user exceeding tag limits and warn him
	function tag_limit_checker() {
		if ($('#tag_tagsinput').children('.tag').length >= 10) {
			$('#tag_tag').prop('disabled', true); //disable input

			$('#tag_tagsinput').addClass('disabled');

			$('#mbTagFeedback').show();
			$('#mbTagFeedback').html('<?php echo $lang['dashboard_msg_4'];?>');
		} else {
			$('#tag_tag').prop('disabled', false); //enable input
			$('#tag_tagsinput').removeClass('disabled');
			$('#mbTagFeedback').hide();
		}
	}

	var maxField = 8; //Input fields increment limitation
	var wrapper = $('#screenshot_inputs'); //Input field wrapper
	$('#add_button').click(function () { //Once add button is clicked
		var randomId = randId();
		var randPlaceholder = "http://i.imgur.com/" + randomId;
		var fieldHTML = '<div class="flex_input col_2">' +
				'<div class="up_group">' +
				'<input id="' + randomId + '" type="text" name="screenshot_links[]" value="" placeholder="<?php echo sprintf($lang['input_placeholder_eg'], '\'+randPlaceholder+\''); ?>.jpg" required/>' +
				'<a href="javascript:void(0)" id="remove_button" class="btn remove_img_btn" title="<?php echo $lang['dashboard_submit_btn_4']; ?>"><?php echo $lang['remove_icon']; ?></a>' +
				'</div>' +
				'<button onclick="showUpModal(\'' + randomId + '\',\'img\')" id="upload_to_imgur" class="btn btn_green" title="<?php echo $lang['dashboard_tooltip_3']; ?>"><?php echo $lang['upload_icon']; ?></button>' +
				'</div>';
		if (wrapper.children().length < maxField) { //Check maximum number of input fields
			$(wrapper).append(fieldHTML); // Add field html
		}
	});

	$(wrapper).on('click', '#remove_button', function (e) { //Once remove button is clicked
		e.preventDefault();
		$(this).parent('div').parent('div').remove(); //Remove field html
	});


	$(document).ready(function () {
		<?php
		//If we are updating an existing entry, pre select the targetted version that was provided when submitting
		if ($viewType == 2): ?>
		addVer();
		<?php endif; ?>

		//Markdown editor load
		MBEditor.wmdBase();
		MBEditor.Util.startEditor();
	});


	function saveEdit() {
		$('form[data-autosubmit][id=addon_submission]').autosubmit();
	}



	function submitted() {
		<?php if(isset($_GET['mod'])) { ?>
			window.location.hash = 'mod_all';
		<?php } elseif ($viewType == 2){ ?>
			window.location.hash = 'dashboard_all';
		<?php } else { ?>
			var $generatedUrl = generatePageUrl(window.location.hash.replace('#', ''));
			loadPageGet($generatedUrl, "action=submit_success");
		<?php } ?>
	}
</script>
