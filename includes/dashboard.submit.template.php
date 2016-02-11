<?php
$no_guests = true; //kick off the guests
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';

$ALL_MBVERSION_DASHBOARD = getVersionInfo(0, "byAllReleases");

//check if edit GET request is made, if not we don't want UNDEFINED ERROR to pop up! so define the variable
if (isset($_GET['view'])) {
	if ($_GET['view'] == "update" && isset($_GET['id'])) {
		$viewType = 2; //update mode
		include './dashboard.tasks.php';
		require_once $siteRoot . 'classes/Addon.php';
		$addon = new Addon(); //create an instance of the addondashboard class
		$data = $addon->getAddonInfo($_GET['id'])[0];

		$screenshot_array = explode(",", $data['image_links']);

	}
} else {
	$viewType = 0; //update mode
}
?>
<form action="../includes/dashboard.tasks.php" method="post" data-autosubmit>
	<div id="blur_content_id"
	class="content_inner_wrapper_admin editmode_wide <?php if ($viewType == 2): ?>dashboard_margin_wrapper_inline<?php endif; ?>">
	<div class="admin_margin_wrapper">
		<div class="infocard_header">
			<h3><?php if ($viewType == 2) echo $lang['217']; else echo $lang['101']; ?></h3>
			<p><?php if ($viewType == 2) echo $lang['218']; else echo $lang['102']; ?></p>
		</div>
		<div class="infocard_header dark_grey">
			<h3><?php echo $lang['103']; ?></h3>
		</div>
		<ul class="form">
			<li>
				<label for="type"><p>Addon type *</p>
					<select name="type" id="type">
						<?php
						foreach ($main_menu['add-ons']['sub_menu'] as $key => $menu_addon) {
							$type_selection_text = "";
							if (isset($data)) {
								if ($data['addon_type']==Slug($menu_addon['title'])) {
									$type_selection_text = "selected";
								}
							}

							
							echo "<option value=\"" . Slug($menu_addon['title']) . "\" ". $type_selection_text .">" . $menu_addon['title'] . "</option>";
						}
						?>
					</select>
				</label>
			</li>
		</ul>
		<script type="text/javascript">
			$(document).ready(function () {
				showSpecial();
			});
			$('#type').on('change', function (event) {
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

		<div class="infocard_header dark_grey">
			<h3><?php echo $lang['104']; ?></h3>
		</div>
		<ul class="form">
			<li>
				<label for="title"><p><?php echo $lang['105']; ?></p>
					<input type="text" id="title" maxlength="80" name="title" required="required"
					value="<?php if ($viewType == 2) echo $data['addon_title']; ?>"/>
				</label>
			</li>
			<li>
				<label for="description"><p><?php echo $lang['106']; ?></p></label>
				<p class="show_info info_yellow">
					<?php echo $lang['107']; ?>
				</p>
				<textarea type="text" id="description" maxlength="600" name="description" required="required"
				style="width:100%; min-height:200px;"
				onkeyup="$('#description_count').text(600 - this.value.length+'/600')"><?php if ($viewType == 2) echo $data['short_description']; ?></textarea>
				<p id="description_count" style="text-align:right"></p>

			</li>
			<li>
				<p><?php echo $lang['108']; ?></p>
				<p class="show_info">
					<?php echo $lang['109']; ?>
				</p>
				<input id="mbSupportedVer" name="mbSupportedVer" type="hidden" value=""/>
				<select id="multipleVer" multiple="multiple" on>
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
					<p id="mbVerFeedback" class="fadeInUp animated show_info info_green" style="display:none"></p>
				</li>
				<li>
					<label for="addonver"><p><?php echo $lang['110']; ?></p>
						<p class="show_info">
							<?php echo $lang['111']; ?>
						</p>
						<input id="addonver" maxlength="15" min="1" name="addonver" type="number" placeholder="eg. 1.0"
						value="<?php if ($viewType == 2) echo $data['addon_version']; ?>"/>
					</label>
				</li>
				<li>
					<label for="tag"><p><?php echo $lang['112']; ?></p>
						<p class="show_info">
							<?php echo $lang['113']; ?>
						</p>
						<p class="show_info info_yellow">
							<?php echo $lang['114']; ?>
						</p>
						<input id="tag" maxlength="150" name="tag" type="text" placeholder="eg. metro, modern, elegant"
						value="<?php if ($viewType == 2) echo $data['tags']; ?>"/>
					</label>
				</li>
				<li id="addon_special" style="display:none;">
					<label for="color"><p>Skin accent color</p>
						<?php

						foreach ($color_codes as $key => $color): 
							$color_selection_text = "";
						if ($viewType == 2) {
							if (Slug($data['COLOR_ID']) == Slug($color['name'])) {
								$color_selection_text = "checked";
							}
						} ?>
						<input type="radio" name="color" id="color" value="<?php echo Slug($color['name']); ?>" style="background:<?php echo $color['value']; ?>" <?php echo $color_selection_text; ?>>
					<?php endforeach; ?>
					<div id="clear"></div>
				</label>
			</li>
		</ul>
		<div class="infocard_header dark_grey">
			<h3><?php echo $lang['115']; ?></h3>
		</div>
		<ul class="form">
			<li>
				<label for="dlink"><p><?php echo $lang['116']; ?></p>
					<p class="show_info info_yellow">
						<?php echo $lang['117']; ?>
					</p>
					<div class="left_float">
						<input type="url" id="dlink" name="dlink" required="required"
						value="<?php if ($viewType == 2) echo $data['download_links']; ?>"/>
					</div>
					<div class="right_float float_2">
						<a href="javascript:void(0);" onclick="showUpModal('dlink','file')" id="upload_to_mediafire"
						class="btn btn_blue input_add" title="<?php echo $lang['213']; ?>"><img
						src="<?php echo $siteUrl . "img/mf-flame-white.png"; ?>">&nbsp;&nbsp;<?php echo $lang['212']; ?></a>
					</div>
					<div id="clear"></div>
				</label>
			</li>
			<li>
				<label for="thumb"><p><?php echo $lang['208']; ?></p></label>
				<p class="show_info">
					<?php echo $lang['209']; ?>
				</p>
				<div class="left_float">
					<input id="thumb" type="text" name="thumb" value="<?php if ($viewType == 2) echo $data['thumbnail']; ?>"
					placeholder="eg. http://i.imgur.com/sdfsdf43gh5.jpg"/>
				</div>
				<div class="right_float float_2">
					<a href="javascript:void(0);" onclick="showUpModal('thumb','img')" id="upload_to_imgur" class="btn btn_green input_add"
					title="<?php echo $lang['124']; ?>"><i class="fa fa-upload"></i> <?php echo $lang['210']; ?></a>
				</div>
				<div id="clear"></div>

			</li>
			<li class="screenshot_multiple_wrap">
				<label for="imglink"><p><?php echo $lang['118']; ?></p></label>
				<p class="show_info">
					<?php echo $lang['119']; ?>
				</p>
				<div id="screenshot_inputs">
					<?php 
					if ($viewType == 2) :
						foreach ($screenshot_array as $key => $screenshots): 
							$rand_container_id = uniqid();
						?>
						<div class="screenshot_input_box">
							<div class="left_float">
								<input id="<?php echo $rand_container_id; ?>" type="text" name="screenshot_links[]" value="<?php echo $screenshots; ?>" placeholder="eg. http://i.imgur.com/<?php echo $rand_container_id; ?>.jpg"/>
							</div>
							<div class="right_float">
								<?php if ($key==0): ?>
									<a href="javascript:void(0);" id="add_button" class="btn btn_blue input_add" title="<?php echo $lang['126']; ?>"><i
										class="fa fa-plus-circle"></i>&nbsp;&nbsp;&nbsp; Add more</a>
									<?php else: ?>
										<a href="javascript:void(0);" id="remove_button" class="btn btn_red input_add" title="Add field"><i class="fa fa-minus-circle"></i>&nbsp;&nbsp;&nbsp; Remove</a>
									<?php endif; ?>
								</div>
								<div class="right_float_2">
									<a href="javascript:void(0);" onclick="showUpModal('<?php echo $rand_container_id; ?>','img')" id="upload_to_imgur"
										class="btn btn_green input_add" title="<?php echo $lang['124']; ?>"><i class="fa fa-upload"></i></a>
									</div>
									<div id="clear"></div>
								</div>
							<?php endforeach; 
							else: ?>
							<div class="screenshot_input_box">
								<div class="left_float">
									<input id="vfda54huk" type="text" name="screenshot_links[]" value="" placeholder="eg. http://i.imgur.com/vfda54huk.jpg"/>
								</div>
								<div class="right_float">
									<a href="javascript:void(0);" id="add_button" class="btn btn_blue input_add" title="<?php echo $lang['126']; ?>"><i
										class="fa fa-plus-circle"></i>&nbsp;&nbsp;&nbsp; Add more</a>
									</div>
									<div class="right_float_2">
										<a href="javascript:void(0);" onclick="showUpModal('vfda54huk','img')" id="upload_to_imgur"
										class="btn btn_green input_add" title="<?php echo $lang['124']; ?>"><i class="fa fa-upload"></i></a>
									</div>
									<div id="clear"></div>
								</div>
							<?php endif; ?>
						</div>

					</li>
				</ul>
				<div class="infocard_header dark_grey">
					<h3><?php echo $lang['120']; ?></h3>
				</div>
				<ul class="form">
					<li>
						<label for="support"><p><?php echo $lang['199']; ?></p>
							<p class="show_info">
								<?php echo $lang['200']; ?>
							</p>
							<input type="url" id="support" name="support" value="<?php if ($viewType == 2) echo $data['support_forum']; ?>"/>
						</label>
					</li>
					<li>
						<label for="wmd-input"><p><?php echo $lang['121']; ?></p>
							<p class="show_info info_yellow">
								<?php echo $lang['122']; ?>
							</p>
							<div id="wmd-editor" class="wmd-panel">
								<div id="wmd-button-bar"></div>
								<textarea id="wmd-input" name="readme"
								onkeyup="$('#wmd-input_count').text(5000 - this.value.length+'/5000')"><?php if ($viewType == 2) echo $data['readme_content']; ?></textarea>
							</div>
							<p id="wmd-input_count" style="text-align:right"></p>
						</label>
						<div id="wmd-preview" class="wmd-panel markdownView"></div>
					</li>
				</ul>
				<ul class="form">
					<li>
						<label for="imp_msg"><p><?php echo $lang['214']; ?></p>
							<p class="show_info info_yellow">
								<?php echo $lang['215']; ?>
							</p>
							<textarea id="imp_msg" name="important_note" maxlength="200" style="width:100%; min-height:100px;"
							onkeyup="$('#imp_msg_count').text(200 - this.value.length+'/200')"><?php if ($viewType == 2) echo $data['important_note']; ?></textarea>
							<p id="imp_msg_count" style="text-align:right"></p>
						</label>
					</li>
				</ul>
				<ul class="form">
					<li>
						<button class="btn btn_blue" type="submit" id="submit" style="padding:15px"
						onclick="saveEdit()"><?php echo $lang['178']; ?></button>
						<p class="show_info info_red">
							<?php echo $lang['123']; ?>
						</p>
					</li>
				</ul>
			</div>
		</div>
		<?php if ($viewType == 2): ?>
			<input type="hidden" name="modify_type" value="update" />
			<input type="hidden" name="record_id" value="<?php echo $data['ID_ADDON']; ?>" />
		<?php else: ?>
			<input type="hidden" name="submit" value="true"/>
		<?php endif; ?>
	</form>
	<div id="clear"></div>
	<div id="upView" class="modalBox iw-modalBox fadeIn animated"></div>

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
			$('#mbVerFeedback').html('<?php echo $lang['125'];?> <b>' + $selectedVer + '</b>');
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
			var fieldHTML = '<div class="screenshot_input_box"><div class="left_float"><input id="' + randomId + '" type="text" name="screenshot_links[]" value="" placeholder="eg. ' + randPlaceholder + '.jpg" required/></div><div class="right_float"><a href="javascript:void(0);" id="remove_button" class="btn btn_red input_add" title="Add field"><i class="fa fa-minus-circle"></i>&nbsp;&nbsp;&nbsp; Remove</a></div><div class="right_float_2"><a href="javascript:void(0);"  onclick="showUpModal(\'' + randomId + '\',\'img\')" id="upload_to_imgur" class="btn btn_green input_add" title="Select an image from your computer and upload to imgur"><i class="fa fa-upload"></i></a></div><div id="clear"></div></div>'; //New input field html
			if (wrapper.children().length < maxField) { //Check maximum number of input fields
				$(wrapper).append(fieldHTML); // Add field html
			}
		});
		$(wrapper).on('click', '#remove_button', function (e) { //Once remove button is clicked
			e.preventDefault();
			$(this).parent('div').parent('div').remove(); //Remove field html
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
				showHideOverlay();
				$('#loading_icon').show();
				$('button').attr('disabled', 'disabled'); // disable button
				$.ajax({
					type: form.attr('method'),
					url: form.attr('action'),
					data: form.serialize()
				}).done(function (data) {
					<?php if ($viewType==2): ?>
						$.modalBox.close(); //once the form is submitted, we don't need the modal box anymore
					<?php endif; ?>
					notificationCallback(data);
				}).fail(function (jqXHR, textStatus, errorThrown) {
					showNotification("<b style=\"text-transform: uppercase;\">" + textStatus + "</b> - " + errorThrown, "error", "red_color");
				}).always(function () {
					$('#loading_icon').hide();
					$('button').removeAttr('disabled');
					showHideOverlay();//show overlay while loading
					<?php if ($viewType==2): ?>
					/* Reload the page with ajax whenever an update saved */
					$dataUrl = $('a[href="' + window.location.hash + '"]');
					$generatedUrl = generateUrl ($dataUrl.attr('data-load-page'));
					loadPageGet($generatedUrl, (!!$dataUrl.attr('data-get-req'))?$dataUrl.attr('data-get-req'): "");
				<?php endif; ?>
				});
			});
	}
	return false;
})(jQuery)

</script>