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

if (!isset($_POST['id']))
	die('Not Available!');
$inputId = $_POST['id'];
?>
<style>
	.bottom_panel_img {
		position: absolute;
		width: 100%;
		bottom: 0px;
		box-sizing: border-box;
		padding: 10px;
	}
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
		content: "\f1c6";
		font-family: FontAwesome;
		font-size: 90px;
		color: #C3D0DA;
		font-weight: normal;
		padding: 20px 20px 5px 20px;
		display: block;
	}

	.dropzone:hover {
		background: #F2F4F5;
	}
	.dropzone input[type="file"] {
		height: 100%;
		left: 0;
		outline: none;
		opacity: 0;
		position: absolute;
		top: 0;
		width: 100%;
		cursor:pointer;
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
<div class="main_mediafire_upload_body">
<?php if (IMGUR_UPLOAD_ON): ?>
	<div class="infocard_header grey_color">
		<h3><?php echo $lang['400']; ?> <img src="<?php echo $siteUrl."img/mf-logo-dark-bg.png"; ?>"></h3>
	</div>
	<div class="infocard_header dark_grey">
		<p><?php echo $lang['401']; ?> <u><a href="http://creativecommons.org/licenses/by-sa/3.0/" target="blank">cc by-sa 3.0</a></u></p>
	</div>
	<div class="img-drop-wrap">
		<div class="dropzone"><h2><?php echo $lang['402']; ?></h2><p><?php echo $lang['403']; ?></p>
			<form action="../includes/upload.tasks.php" method="POST" enctype="multipart/form-data" id="uploadform">
				<input type="file" id="file_input" accept=".rar, .zip, .7z, .tgz" name="file">
				<input type="hidden" name="target" value="mediafire">
			</form>
		</div>
	</div>
<?php else: ?>
<div class="infocard_header darkred_color">
	<h3><?php echo $lang['404']; ?></h3>
	<p><?php echo $lang['405']; ?></p>
</div>
<?php exit(); endif; ?>

</div>
<div id="bottom_bar_imgur" class="bottom_panel_img"></div>
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
	var file = $('#file_input');

	file.on('change', function(e) {
		e.preventDefault();
		if (e.target && e.target.nodeName === 'INPUT' && e.target.type === 'file') {
			target = e.target.files[0]; //cache the file
			checkTypes(target); //check mime type
		}
	});


	function checkTypes (file) {
		var re = /(\.rar|\.zip|\.7z|\.tgz)$/i;
		if (re.exec(file.name)) {
			console.log("Upload Started");
			$('#upView').addClass('busy'); //show busy icon
			//now comes the main form submission
			uploadMediafire();
		} else {
			alert("Only supports .rar, .zip, .7z, .tgz"); 
		}
	}


	function uploadMediafire () {
		var form = $('#uploadform');
		var formData =  new FormData(document.getElementById("uploadform"));
		hideNotification ();
		$.ajax({
			type: form.attr('method'),
			url: form.attr('action'),
			processData: false,
			contentType: false,
			cache: false,
			data: formData
		}).done(function(data) {
			uploadCallback(data);
			}).fail(function(jqXHR, textStatus, errorThrown) {
				showNotification("<b style=\"text-transform: uppercase;\">"+textStatus+"</b> - "+errorThrown, "error", "red_color");
			}).always(function() {
				$('#upView').removeClass('busy');
			});
	}

	function uploadCallback (data) {
		var obj = jQuery.parseJSON(data);
		if (obj.status == 0) {
			showNotification(obj.data, "error", "red_color");
		} else if (obj.status == 1){
			showNotification(obj.data, "success", "green_color");
			console.log("URL recieved from server: "+obj.url);
			$('#<?php echo $inputId; ?>').val(obj.url);
			$.modalBox.close();
		}
	}
</script>