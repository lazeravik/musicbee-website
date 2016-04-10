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
require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';

//check if edit GET request is made, if not we don't want UNDEFINED ERROR to pop up! so define the variable
if (isset($_GET['view'])) :

	if($_GET['view']=="edit"){
	$viewType = 0; //stable edit mode
} elseif($_GET['view']=="editbeta") {
	$viewType = 1;  //beta edit mode
} elseif ($_GET['view']=="update" && isset($_GET['id'])) {
	$viewType = 2; //update mode
	include __dir__.'/adminpanel.tasks.php';
	$infoArray = getVersionInfo($_GET['id'], "byId"); //Request version info by ID
	$currentVersion = getVersionInfo(0, "byCurrentVersion"); //Get the current 
}
?>
<div class="content_inner_wrapper_admin editmode_wide <?php if($viewType==2):?>admin_margin_wrapper_inline<?php endif;?>">
	<div class="admin_margin_wrapper <?php if($viewType==2):?>admin_margin_wrapper_inline<?php endif;?>">
		<div class="infocard_header <?php if($viewType==0):?>green_color<?php elseif($viewType==1):?>darkred_color<?php elseif($viewType==2):?>blue_color<?php endif;?>">
			<?php if($viewType==0):?>
				<h3>Edit MusicBee <b>Stable Release</b> Info</h3>
			<?php elseif($viewType==1):?>
				<h3>Edit MusicBee <b>Beta Release</b> Info</h3>
			<?php elseif($viewType==2):?>
				<h3>Update MusicBee Version <?php echo $infoArray[0]['version']; ?> Info</h3>
				<p>Released on <?php echo $infoArray[0]['release_date']; ?></p>
			<?php endif;?>
		</div>
		<form action="../includes/admin.tasks.php" method="post" data-autosubmit>
			<ul class="form">
				<li>
					<label for="appname"><p>App Name</p>
						<input type="text" id="appname" name="appname" maxlength="80" required="required" value="<?php if($viewType==0) echo $release['stable']['appname']; elseif($viewType==1) echo $release['beta']['appname']; elseif($viewType==2) echo $infoArray[0]['appname'];?>"/>
					</label>
				</li>
				<li>
					<label for="ver"><p>Version</p>
						<input type="text" id="ver" maxlength="80" name="ver" required="required" value="<?php if($viewType==0) echo $release['stable']['version']; elseif($viewType==1) echo $release['beta']['version']; elseif($viewType==2) echo $infoArray[0]['version'];?>"/>
					</label>
				</li>
				<li>
					<label for="os"><p>Supported OS</p>
						<input type="text" id="os" name="os" maxlength="80" required="required" value="<?php if($viewType==0) echo $release['stable']['os']; elseif($viewType==1) echo $release['beta']['os']; elseif($viewType==2) echo $infoArray[0]['supported_os'];?>"/>
					</label>
				</li>
			</ul>
			<?php if($viewType==0 || $viewType==1 || ($viewType==2 && $currentVersion[0]['version']==$infoArray[0]['version'])): ?>
				<div class="infocard_header teal_color">
					<h3><i class="fa fa-link"></i>&nbsp; Installer Download Links</h3>
				</div>
				<ul class="form">
					<li>
						<label for="ilink1"><p>Download Link</p>
							<input type="url" id="ilink1" spellcheck="false" name="ilink1" required="required" value="<?php if($viewType==0 || $viewType==2) echo $release['stable']['link1']; elseif($viewType==1) echo $release['beta']['link1'];?>"/>
						</label>
					</li>
					<?php if($viewType!=1): ?>
						<li>
							<label for="ilink2"><p>Mirror link 1</p>
								<input type="url" id="ilink2" spellcheck="false" name="ilink2" value="<?php echo $release['stable']['link2'];?>"/>
							</label>
						</li>
						<li>
							<label for="ilink3"><p>Mirror link 2</p>
								<input type="url" id="ilink3" spellcheck="false" name="ilink3" value="<?php echo $release['stable']['link3'];?>"/>
							</label>
						</li>
						<div class="infocard_header dark_grey">
							<h3><i class="fa fa-link"></i>&nbsp; Portable Download Links</h3>
						</div>
						<li>
							<label for="plink1"><p>Portable link</p>
								<input type="url" id="plink1" spellcheck="false" name="plink1" value="<?php echo $release['stable']['link4'];?>"/>
							</label>
						</li>
					</ul>
				<?php endif; 
				endif; 
				if($viewType!=1): ?>
				<ul class="form">
					<div class="infocard_header dark_grey">
						<h3><i class="fa fa-link"></i>&nbsp; Optional</h3>
					</div>
					<li>
						<p class="show_info">
							If this MusicBee version bring some breaking change or include new features, then check it. <b>This will enable addon author to target this MusicBee version.</b><br/>
							<b>Do not check it for minor changes(eg. ver3.0 to ver3.1).</b> Major changes like ver 2.5 to ver 3.0
						</p>
						<div class="left_label">
							<p class="chkbox_text">Is this major MusicBee version</p>
						</div>
						<div class="right_toogle">
							<input class="cmn-toggle cmn-toggle-round" type="checkbox" id="major" name="major" <?php if($viewType==2) echo ($infoArray[0]['major']==1)?"checked":""; ?>>
							<label for="major"></label>
						</div>
						<div id="clear"></div>
					</li>
					<?php if($viewType==2): ?>
						<hr class="line"/>
						<li>
							<p class="show_info">Every Major version is available by default. You can change them here.</p>
							<div class="left_label">
								<p>Dashboard Availability</p>
							</div>
							<div class="right_toogle">
								<input class="cmn-toggle cmn-toggle-round" type="checkbox" id="dashboard" name="dashboard" <?php echo ($infoArray[0]['dashboard_availablity']==1)?"checked":""; ?>/>
								<label for="dashboard"></label>
							</div>
							<div id="clear"></div>
						</li>
						<hr class="line"/>

					<?php endif; ?>
					<li>
						<label for="wmd-input"><p>Release Note</p>
							<p class="show_info">
								Release Notes(or change logs) are optional for minor releases.<br/>
								Basic markdown is allowed. <b>No HTML is allowed</b>. Even though image is allowed, it is not recommended
							</p>
							<div id="wmd-editor" class="wmd-panel">
								<div id="wmd-button-bar"></div>
								<textarea id="wmd-input" name="note"><?php if($viewType==2) echo $infoArray[0]['release_note']; ?></textarea>
							</div>
						</label>
						<div id="wmd-preview" class="wmd-panel markdownView"></div>
					</li>

				<?php elseif($viewType==1): ?>
					<div class="infocard_header dark_grey">
						<h3><i class="fa fa-comment"></i>&nbsp; Extra Message (optional)</h3>
					</div>
					<li>
						<label for="message"><p>Message</p>
							<p class="show_info">
								Add any extra note, maybe any known bugs or breaking changes, it will display on the download page.<br/>
								<b>Also mention if previous stable release is needed or not.</b><br/>
								<b><i>No Formatting is allowed.</i></b>
							</p>
							<textarea id="message" name="message" style="width:100%; min-height:200px;"><?php echo $release['beta']['message']; ?></textarea>
						</label>
					</li>  
				<?php endif; ?>                   
				<li>
					<?php if($viewType==0||$viewType==1): ?>
						<button title="Save" accesskey="s" class="btn btn_blue" id="submit" style="padding:15px" onclick="saveEdit();">Save and Add to archive</button>
						<?php if($viewType==0): ?>
							<p class="show_info info_yellow">
								If you want to only correct mistakes <b>Go to <i>All MusicBee Release</i>, and edit them there</b>.
							</p>
							<input type="hidden" name="isnew" value="true" />
						<?php endif; ?>
					<?php elseif($viewType==2): ?>
						<button title="Save" accesskey="s" class="btn btn_blue" id="submit" style="padding:15px" onclick="saveEdit();">Update</button>
						<input type="hidden" name="id_allversion" value="<?php echo $infoArray[0]['ID_ALLVERSIONS']; ?>">
					<?php endif; ?>
				</li>
				<input type="hidden" name="save" value="<?php if($viewType==0 || $viewType==2) echo "stable"; elseif($viewType==1) echo "beta";?>" />
			</ul>
		</form>
	</div>
</div>
<div id="clear"></div>
<?php if($viewType!=1): ?>
	<script src="<?php echo $link['url']; ?>scripts/markdownEditor.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			MBEditor.wmdBase();
			MBEditor.Util.startEditor();
		});
	</script>
<?php endif;?>
<script type="text/javascript">
	function saveEdit () {
		$('form[data-autosubmit]').autosubmit();
	}

	(function($) {
		$.fn.autosubmit = function() {
			this.submit(function(event) {
				event.preventDefault();
				event.stopImmediatePropagation(); //This will stop the form submit twice
				var form = $(this);
				hideNotification ();
				showHideOverlay();
				$('#loading_icon').show();
				$('button').attr('disabled', 'disabled'); // disable button
				$.ajax({
					type: form.attr('method'),
					url: form.attr('action'),
					data: form.serialize()
				}).done(function(data) {
					<?php if ($viewType==2): ?>
						$.modalBox.close(); //once the form is submitted, we don't need the modal box anymore
					<?php endif; ?>
					notificationCallback(data);
				}).fail(function(jqXHR, textStatus, errorThrown) {
					showNotification("<b style=\"text-transform: uppercase;\">"+textStatus+"</b> - "+errorThrown, "red_color");
				}).always(function() {
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
<?php endif;?>