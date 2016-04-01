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
require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';

if (!isset($_POST['id'])){
	die('Not Available!');
}
$inputId = $_POST['id'];
?>
<style>
	.dropzone h2 {
		font-weight: bold;
		color: #6C7984;
		margin:0px;
	}

	.dropzone p {
		line-height: 30px;
		margin: 0;
		color: #545E67;
		font-size: 15px;
		height: initial;
		width: initial;
	}

	.dropzone::before {
		content: "\f03e";
		font-family: FontAwesome;
		font-size: 90px;
		color: #C3D0DA;
		font-weight: normal;
		padding: 20px 20px 0px 20px;
		display: block;
	}

	.dropzone:hover {
		background: #F2F4F5;
	}


	.loading-modal {
		background-color: rgba( 255, 255, 255, .8 );
		display: none;
		position: fixed;
		z-index: 1000;
		top: 0;
		left: 0;
		height: 100%;
		width: 100%;
	}

	.loading-image {
		position: absolute;
		top: 50%;
		left: 50%;
		margin: -16px 0 0 -16px;
	}

	.busy .loading-modal {
		display: block;
	}
	.busy_overlay {
		display:none;
	}
	.busy .busy_overlay {
		display: block;
		background:rgba(0, 0, 0, 0.8) none repeat scroll 0% 0%;
		width: 100%;
		height: 100%;
		position: absolute;
		top: 0px;
		left: 0px;
		box-sizing: border-box;
		z-index:100;
	}
	.sk-circle {
		margin: 180px auto;
		width: 40px;
		height: 40px;
		position: relative;
	}

</style>
<div class="main_imgur_upload_body">
<?php if (IMGUR_UPLOAD_ON): ?>
	<div class="show_info custom">
		<h3><?php echo $lang['406']; ?><img src="<?php echo $link['url']."img/logo-light.png"; ?>"></h3>
	</div>
	<div class="show_info info_darkgrey">
		<?php echo $lang['407']; ?> <u><a href="http://creativecommons.org/licenses/by-sa/3.0/" target="blank">cc by-sa 3.0</a></u>
	</div>
	<div class="img-drop-wrap">
		<div class="dropzone"><h2><?php echo $lang['408']; ?></h2><p><?php echo $lang['409']; ?></p>
			<form action="../includes/upload.tasks.php" method="POST" enctype="multipart/form-data" id="uploadform">
				<input type="file" id="img_input" accept="image/*" name="img">
				<input type="hidden" name="target" value="imgur">
			</form>
		</div>
	</div>
<?php else: ?>
<div class="show_info danger">
	<h3><?php echo $lang['410']; ?></h3>
	<p><?php echo $lang['411']; ?></p>
</div>
<?php exit(); endif; ?>

</div>
<div class="busy_overlay fadeIn animated">
	<div class="sk-circle">
		<div class="sk-circle1 sk-child"></div>
		<div class="sk-circle2 sk-child"></div>
		<div class="sk-circle3 sk-child"></div>
		<div class="sk-circle4 sk-child"></div>
		<div class="sk-circle5 sk-child"></div>
		<div class="sk-circle6 sk-child"></div>
		<div class="sk-circle7 sk-child"></div>
		<div class="sk-circle8 sk-child"></div>
		<div class="sk-circle9 sk-child"></div>
		<div class="sk-circle10 sk-child"></div>
		<div class="sk-circle11 sk-child"></div>
		<div class="sk-circle12 sk-child"></div>
	</div>

</div>

<script type="text/javascript">
	var img = $('#img_input');

	img.on('change', function(e) {
		e.preventDefault();
		if (e.target && e.target.nodeName === 'INPUT' && e.target.type === 'file') {
			target = e.target.files[0]; //cache the file
			checkTypes(target); //check mime type
		}
	});


	function checkTypes (file) {
		if (file.type.match(/image/) && file.type !== 'image/svg+xml') {
			$('#upView').addClass('busy'); //show busy icon
			//now comes the main form submission
			uploadImgur();
		}
	}


	function uploadImgur () {
		var form = $('#uploadform');
		hideNotification ();

		$.ajax({
			type: form.attr('method'),
			url: form.attr('action'),
			processData: false,
			contentType: false,
			data: form.serializefiles()
		}).done(function(data) {
			uploadCallback(data);
					//showNotification(data, "success", "blue_color");
				}).fail(function(jqXHR, textStatus, errorThrown) {
					showNotification("<b style=\"text-transform: uppercase;\">"+textStatus+"</b> - "+errorThrown, "error", "red_color");
				}).always(function() {
					$('#upView').removeClass('busy');
				});
			}

			(function($) {
				$.fn.serializefiles = function() {
					var obj = $(this);
					/* ADD FILE TO PARAM AJAX */
					var formData = new FormData();
					$.each($(obj).find("input[type='file']"), function(i, tag) {
						$.each($(tag)[0].files, function(i, file) {
							formData.append(tag.name, file);
						});
					});
					var params = $(obj).serializeArray();
					$.each(params, function (i, val) {
						formData.append(val.name, val.value);
					});
					return formData;
				};
			})(jQuery);

			function uploadCallback (data) {
				var obj = jQuery.parseJSON(data);
				if (obj.status == 0) {
					showNotification(obj.data, "error", "red_color");
				} else if (obj.status == 1){
					showNotification(obj.data, "success", "green_color");
					$('#<?php echo $inputId; ?>').val(obj.url);
					$.modalBox.close();
				}
			}
</script>